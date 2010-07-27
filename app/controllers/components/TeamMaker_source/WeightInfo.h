#ifndef WEIGHTINFO_H
#define WEIGHTINFO_H

#include <map>
#include <vector>
#include "XMLFile.h"

using namespace std;

class WeightInfo {
public:
	void Init(const XMLNode& rootNode) {
		for(unsigned int i = 0; i < rootNode.childNodes.size(); i++) {
			if(rootNode.childNodes[i].name == "question") {
				int qID = rootNode.childNodes[i].AttribValueByNameInt("id");
				titles[qID] = rootNode.childNodes[i].AttribValueByName("title","??");
				if(qID == minority_id) {
					for(unsigned int j = 0; j < rootNode.childNodes[i].childNodes.size(); j++) {
						const XMLNode& x = rootNode.childNodes[i].childNodes[j];
						minority_weights[x.AttribValueByNameInt("id")] = -TransformWeight(x.AttribValueByNameInt("value"));
					}
				} else {
					qIDs.push_back(qID);
					weights[qID] = TransformWeight(rootNode.childNodes[i].ChildByName("weight").AttribValueByNameInt("value"));
				}
			}
		}
	}

	// No longer useful since PHP gives us real weights now
	double TransformWeight(int i) {
		return i;
	}


	int minority_id;
	vector<int> qIDs;
	mutable map<int, string> titles;
    mutable map<int, double> minority_weights;
	mutable map<int, double> weights;
};

#endif
