/* TeamMaker creates teams based on weighted criteria.
 * Copyright (C) 2005 Rose-Hulman Institute of Technology
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the license, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it is useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public LIcense for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301   USA
 *
 * Richard Layton
 * Rose-Hulman Institute of Technology
 * 5500 Wabash Ave.
 * Terre Haute, IN 47803-3920
 * E-mail: layton@rose-hulman.edu
 */

#ifndef TEAMMAKER_H
#define TEAMMAKER_H

#include <algorithm>
#include <iostream>
#include <fstream>
#include <limits.h>
#include "Team.h"

const int NumTopLevelTries = 50;

class TeamMaker {
public:
	TeamMaker() {}

	vector<Team*> teams;
	vector<Team*> fixed_teams;
	WeightInfo wi;
	int numTeams;

	void Process(string inFilename, string outFilename) {
		ofstream scoreFile((outFilename + ".scores").c_str());
		ofstream verboseLog("verbose.txt");

		cout << "Loading XML file" << endl;
		XMLFile infile(inFilename);
		const XMLNode& rootNode = infile.rootNode.ChildByName("team_input");

		numTeams = rootNode.AttribValueByNameInt("num_groups");
		if(numTeams == 0) throw string("Number of teams == 0");
		cout << "Processing to create " << numTeams << " teams" << endl;

		wi.minority_id = rootNode.AttribValueByNameInt("minority_id",-1);
		cout << "Found minority id == " << wi.minority_id << endl;

		cout << "Loading students..." << endl;
		vector<Student*> students;
		for(unsigned int i = 0; i < rootNode.childNodes.size(); i++) {
			if(rootNode.childNodes[i].name == "student") {
				students.push_back(new Student(rootNode.childNodes[i]));
			}
		}
		cout << "done" << endl;

		cout << "Initializing weight info..." << endl;
		wi.Init(rootNode);
		cout << "done" << endl;

		cout << "Handling fixed teams..." << endl;
		for(unsigned int i = 0; i < rootNode.childNodes.size(); i++) {
			if(rootNode.childNodes[i].name == "fixed") {
				Team* newFixedTeam = new Team();
				for(unsigned int j = 0; j < rootNode.childNodes[i].childNodes.size(); j++) {
					if(rootNode.childNodes[i].childNodes[j].name != "member") continue;

					// Find the student, remove them from the Student array, put them in
					// this new fixed team
					string fixedName = rootNode.childNodes[i].childNodes[j].AttribValueByName("name");
					if(fixedName == "") continue; //empty spot in team
					vector<Student*>::iterator k;
					for(k = students.begin(); k != students.end(); k++) {
						if((*k)->username == fixedName) {
							newFixedTeam->students.push_back(*k);
							students.erase(k);
							break;
						}
					}
				}
				cout << "Found a fixed team, processed and decremented numTeams" << endl;
				fixed_teams.push_back(newFixedTeam);
				numTeams--;
			}
		}
		cout << "done" << endl;

		cout << "Trying " << NumTopLevelTries << " top-level creations" << endl;

		double bestScore = INT_MIN;
		vector<Team*> bestSet;

		for(int i = 0; i < NumTopLevelTries; i++) {
			InitEmptyTeams();

			// Shuffle students and form initial distribution
			random_shuffle(students.begin(), students.end());
			for(unsigned int j = 0; j < students.size(); j++)
				teams[j % teams.size()]->students.push_back(students[j]);

			bool flipFlag = true; // did we do any good swaps this loop?
			int numFlipIterations = 0; // This is mostly a safety catch
			while(flipFlag && (numFlipIterations < 20)) {
				flipFlag = false;
				for(unsigned int teamA = 0; teamA < teams.size(); teamA++) {
					for(unsigned int teamB = teamA + 1; teamB < teams.size(); teamB++) {
						for(unsigned int aGuy = 0; aGuy < teams[teamA]->students.size(); aGuy++) {
							for(unsigned int bGuy = 0; bGuy < teams[teamB]->students.size(); bGuy++) {

								double oldMin = Min(teams[teamA]->TeamScore(wi), teams[teamB]->TeamScore(wi));

								swap(teams[teamA]->students[aGuy],teams[teamB]->students[bGuy]);

								double newMin = Min(teams[teamA]->TeamScore(wi), teams[teamB]->TeamScore(wi));
								
								if(newMin > oldMin) {
									flipFlag = true;
								} else {
									swap(teams[teamA]->students[aGuy],teams[teamB]->students[bGuy]);
								}
							}
						}
					}
				}
				numFlipIterations++;
			}
			cout << "Used " << numFlipIterations << " flip iterations" << endl;

			double score = GetCurrentScore();
			if(score > bestScore) {
				cout << "Found a new maximum (" << score << ") at iteration " << i << endl;
				DeleteTeams(bestSet);
				bestSet = teams;
				bestScore = score;
			} else {
				DeleteTeams(teams);
			}
		}

		cout << "Top-level creation done" << endl;
		teams = bestSet;

		cout << "Merging " << (int) fixed_teams.size() << " fixed teams..." << endl;
		for(unsigned int i = 0; i < fixed_teams.size(); i++) {
			teams.push_back(fixed_teams[i]);
		}

		cout << "Writing output files" << endl;
        ofstream outFile(outFilename.c_str());
		for(unsigned int i = 0; i < teams.size(); i++) {
            outFile << teams[i]->ToString() << endl;
			verboseLog << teams[i]->ExtraInfo(wi) << endl;
		}

		// Write uber-summary
		scoreFile << "<table border=\"1\" cellpadding=\"2\">";
		
		// Top row: [blank] question titles [total]
		scoreFile << "<tr><td></td>";
		if(wi.minority_id != -1) {
			scoreFile << "<td>" << wi.titles[wi.minority_id] << "</td>";
		}
		for(unsigned int i = 0; i < wi.qIDs.size(); i++) {
			// vertical text only works in IE, but it's just a nice-to-have
			scoreFile << "<td><span class=\"vertical\">" << wi.titles[wi.qIDs[i]] << "</span></td>" << endl;
		}
		scoreFile << "<td>Total</td></tr>" << endl;

		// Second row: team number and weights
		scoreFile << "<tr><td>Team / Weight</td>";
		if(wi.minority_id != -1) {
			scoreFile << "<td>(multiple)</td>";
		}
		for(unsigned int i = 0; i < wi.qIDs.size(); i++) {
			// <td bgcolor="<?=color_for_weight(x)?>">
			scoreFile << "<td bgcolor=\"<?=" << "color_for_weight(" << wi.weights[wi.qIDs[i]] << ")?>\">";
			scoreFile << wi.weights[wi.qIDs[i]] << "</td>" << endl;
		}
		
		scoreFile << "</tr>" << endl;

        // N rows: Teams with scores followed by total
		for(unsigned int i = 0; i < teams.size(); i++) {
			scoreFile << "<tr><td>Team #" << (i + 1) << "</td>";
			if(wi.minority_id != -1) {
				scoreFile << "<td>" << FormatDouble(teams[i]->MinorityScore(wi)) << "</td>";
			}
			for(unsigned int j = 0; j < wi.qIDs.size(); j++) {
				scoreFile << "<td align=\"center\">";
				double s = teams[i]->QuestionScore(wi.qIDs[j]);
				if(wi.weights[wi.qIDs[j]] == 0) {
					scoreFile << "-";
				} else if(wi.weights[wi.qIDs[j]] > 0) {
					scoreFile << Percent(s);
				} else {
					s = 1 - s;
					scoreFile << Percent(s);
				}
				scoreFile << "</td>";
			}
			scoreFile << "<td>" << FormatDouble(teams[i]->TeamScore(wi)) << "</td>";
			scoreFile << "</tr>";
		}

		scoreFile << "</table>";
		scoreFile.close();

		cout << "Done" << endl;

		outFile.close();
	}

	static string FormatDouble(double d) {
		char buffer[16];
		sprintf(buffer,"%1.2f", d);
		return buffer;
	}

	static string Percent(double d) {
		char buffer[16];
		sprintf(buffer,"%d%%",(int) floor(d*100 + .499));
		return buffer;
	}

	void InitEmptyTeams() {
		teams.clear();
		for(int i = 0; i < numTeams; i++) {
			teams.push_back(new Team());
		}
	}

	void DeleteTeams(vector<Team*> teams) {
		for(unsigned int i = 0; i < teams.size(); i++) {
			delete teams[i];
		}
	}

	double GetCurrentScore() {
		double min = 1000;
		for(unsigned int i = 0; i < teams.size(); i++) {
			double temp = teams[i]->TeamScore(wi);
			if(temp < min) min = temp;
		}
		return min;
	}

};
#endif
