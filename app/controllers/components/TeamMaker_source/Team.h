#ifndef TEAM_H
#define TEAM_H

#define MIN(a,b) (((a) < (b)) ? (a) : (b))

#include <sstream>
#include <vector>
#include "Response.h"
#include "Student.h"
#include "WeightInfo.h"

using namespace std;

class Team {
public:

	// Calculate a fully-weighted score for this team
	double TeamScore(const WeightInfo& wi) const {
		double ret = 0;

		// Calculate the minority value first if there is one
		// MinorityScore() weights itself
		ret += MinorityScore(wi);

		// Calculate the rest of the question values
		for(unsigned int i = 0; i < wi.qIDs.size(); i++) {
			ret += QuestionScore(wi.qIDs[i]) * wi.weights[wi.qIDs[i]];
		}

		return ret;
	}

	// Calculate the unweighted score for the specified question
	double QuestionScore(int qID) const {
		if(students.size() == 0) return 1.0;

		// Defer to type-specific scoring functions
		ResponseType t = students[0]->ResponseByID(qID)->GetType();
		switch(t) {
			case R_MC:
				return MC_QuestionScore(qID);
			case R_CAO:
				return CAO_QuestionScore(qID);
			case R_SCHED:
				return SCHED_QuestionScore(qID);
			case R_TEXT:
				return 0;
			default:
				throw string("Unknown question type");
		}
	}

	// Returns in [0, 1]
	// 1 = everyone different
	// 0 = everyone same
	double MC_QuestionScore(int qID) const {
		// MC Heuristic: How many different groups are represented?
		// i.e. how many frats are represented in this team
		// This is good when N = 4 or 5, bad for higher cases
		map<int, bool> seenThis;
		double numGroups = 0;
		for(unsigned int i = 0; i < students.size(); i++) {
			int respID = students[i]->ResponseByID(qID)->GetResponseID();
			if(seenThis[respID]) continue;
			numGroups++;
			seenThis[respID] = true;
		}
		if(numGroups == 1) return 0;
		if(numGroups == students.size()) return 1;
		return numGroups / ((double) students.size());
	}

	// Returns in [0, 1]
	// 1 = Everyone different
	// 0 = Everyone same
	double CAO_QuestionScore(int qID) const {
		// CAO Heuristic:
		// For each response in the question, count the
		// number of students that answered "yes"
		// If this number is one, make it zero
		// Square this number and add it to the result

		// Gather the responses
		vector<string> responseMasks;
		for(unsigned int i = 0; i < students.size(); i++) {
			CAO_Response* resp = dynamic_cast<CAO_Response*>(students[i]->ResponseByID(qID));
			responseMasks.push_back(resp->GetResponseMask());
		}

		// Make the sum-of-squares figure
		double ret = 0;
		for(unsigned int i = 0; i < responseMasks[0].length(); i++) {
			int count = 0;
			for(unsigned int j = 0; j < responseMasks.size(); j++) {
				if(responseMasks[j][i] == '1') count++;
			}
			if(count == 1) count = 0;
			ret += count * count;
		}

		// The theoretical maximum here is students*students*responses
		// but that's a degenerate case (hopefully). Use students*responses instead
		return 1 - Min(ret / ((double) (students.size() * responseMasks[0].length())), 1.0);
	}

	// Returns in [0, 1]
	// 0 = Lots of free hours together
	// 1 = No free hours together
	double SCHED_QuestionScore(int qID) const {
		// SCHED Heuristic: Number of total hours which all students are available for
		double total = 0;
		
		for(unsigned int i = 0; i < students[0]->ResponseByID(qID)->GetScheduleMask().length(); i++) {
			bool allFree = true;
			for(unsigned int j = 0; j < students.size(); j++) {
				if(students[j]->ResponseByID(qID)->GetScheduleMask()[i] == '1') {
					allFree = false;
					break;
				}
			}
			if(allFree) total++;
		}

		// 60 is a good upper limit?
		return 1 - MIN(total / 60.0, 1.0);
	}

	// Calculated weighted minority score
	// Heuristic: For each minority that has a pair (or more)
	// existing in the group, add (weight)
	// If a minority is by itself, subtract (weight)
	double MinorityScore(const WeightInfo& wi) const {
		double mScore = 0;
		if(wi.minority_id != -1) {
			for(map<int,double>::const_iterator i = wi.minority_weights.begin(); i != wi.minority_weights.end(); ++i) {
				int rid = i->first;
				double weight = i->second;
				int numOfThisClass = 0;
				for(unsigned int j = 0; j < students.size(); j++)
					if(students[j]->ResponseByID(wi.minority_id)->GetSubResponse(rid)) numOfThisClass++;
				if(numOfThisClass >= 2) mScore += weight;
				if(numOfThisClass == 1) mScore -= weight;
			}
		}
		return mScore;
	}

	// Returns a space-delimited list of students
	string ToString() const {
		string ret = "";
		for(unsigned int i = 0; i < students.size(); i++) {
			if(i > 0) ret += " ";
			ret += students[i]->username;
		}
		return ret;
	}


	// Returns a space-delimited list of scores
	// starting with the minority score
    string ScoreSummary(const WeightInfo& wi) const {
		ostringstream os;
		os << MinorityScore(wi) << " ";
		for(unsigned int i = 0; i < wi.qIDs.size(); i++) {
			os << QuestionScore(wi.qIDs[i]) << " ";
		}

		os << '\0';
		return os.str();
	}

	// Returns a more comprehensive breakdown of the scores
	string ExtraInfo(const WeightInfo& wi) const {
		ostringstream os;
		os << "Minority Score: " << MinorityScore(wi) << endl;
		for(unsigned int i = 0; i < wi.qIDs.size(); i++) {
			os << "Question:";
			os << "\tid = " << wi.qIDs[i];
			os << "\tscore: " << QuestionScore(wi.qIDs[i]);
			os << "\tweight: " << wi.weights[wi.qIDs[i]];
			os << endl;
		}

		os << '\0';
		return os.str();
	}

	vector<Student*> students;
};

#endif
