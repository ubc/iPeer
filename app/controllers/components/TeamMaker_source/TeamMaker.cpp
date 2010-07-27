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

#include "TeamMaker.h"
#include <ctime>
int main(int argc, char *argv[]) {
	// Initialize random number generator with unique value.
	srand (time (NULL));
		
	// Check arguments before creating the TeamMaker object to process arguments.
	if (argc == 3) {
		// Create the TeamMaker object.
		TeamMaker* tm = new TeamMaker();
		tm->Process(argv[1], argv[2]);
    } else {
		cerr << argv[0] << " takes two arguments, not " << (argc - 1) << "." << endl;
    }
	return 0;
}
