#ifndef RESPONSE_H
#define RESPONSE_H

#include "XMLFile.h"
#include <map>

using namespace std;

enum ResponseType { R_CAO, R_MC, R_SCHED, R_TEXT };

class Response {
public:
	virtual double ComparisonScore(const Response& rhs) const = 0;
	virtual bool GetSubResponse(int id) const { throw string("GetSubResponse not supported"); }
	virtual string GetResponseMask() const { throw string("GetResponseMask unsupported"); }
	virtual int GetResponseID() const { throw string("GetResponseID unsupported"); }
	virtual string GetScheduleMask() const { throw string("GetScheduleMask unsupported"); }
	virtual ResponseType GetType() const = 0;
	virtual int GetQuestionID() const { return questionID; }

protected:
	int questionID;
};


class MC_Response : public Response {
public:
	MC_Response(const XMLNode& node) {
		questionID = node.AttribValueByNameInt("q_id");
		responseID = node.ChildByName("value").AttribValueByNameInt("id", -1);
	}

	ResponseType GetType() const {
		return R_MC;
	}

	int GetResponseID() const {
		return responseID;
	}

	double ComparisonScore(const Response& rhs) const {
		if(rhs.GetType() != R_MC) throw string("Invalid comparison");
		return responseID == dynamic_cast<const MC_Response&>(rhs).responseID;
	}

protected:
	int responseID;
};

class CAO_Response : public Response {
public:
	CAO_Response(const XMLNode& node) {
		questionID = node.AttribValueByNameInt("q_id");
		for(unsigned int i = 0; i < node.childNodes.size(); i++) {
			if(node.childNodes[i].name != "value") {
				cout << "Warning, found non-value CAO child node" << endl;
				continue;
			}
			int id = node.childNodes[i].AttribValueByNameInt("id");
			bool val = node.childNodes[i].AttribValueByNameBool("answer");
			responses[id] = val;
		}
	}
	
	virtual ~CAO_Response() {
	}

	ResponseType GetType() const {
		return R_CAO;
	}

	string GetResponseMask() const {
		string ret = "";
		for(map<int,bool>::const_iterator i = responses.begin(); i != responses.end(); ++i) {
			if(i->second)
				ret += "1";
			else
				ret += "0";
		}
		return ret;
	}

	bool GetSubResponse(int id) const {
		return responses[id];
	}

	// Must return in 0...1
	double ComparisonScore(const Response& rhs) const {
		if(rhs.GetType() != R_CAO) throw string("Invalid comparison");
		const CAO_Response& crhs = dynamic_cast<const CAO_Response&>(rhs);

		// If neither have any data, return 1.0 (match)
		if(crhs.responses.size() == 0 || responses.size() == 0) return 1.0;

		double numMatches = 0;
		for(map<int,bool>::const_iterator i = responses.begin(); i != responses.end(); ++i) {
			if(crhs.responses[i->first] == i->second) numMatches++;
		}

		return numMatches / ((double) responses.size());
	}

protected:
	mutable map<int, bool> responses;
};

class SCHED_Response : public Response {
public:
	SCHED_Response(const XMLNode& node) {
		questionID = node.AttribValueByNameInt("q_id");
		mask = node.ChildByName("mask").AttribValueByName("value");
	}
	
	virtual ~SCHED_Response() {
	}

	ResponseType GetType() const {
		return R_SCHED;
	}

	string GetScheduleMask() const {
		return mask;
	}

	double ComparisonScore(const Response& rhs) const {
		const SCHED_Response& srhs = dynamic_cast<const SCHED_Response&>(rhs);

		// If either of us are blank, return 1
		if(srhs.mask.length() == 0 || mask.length() == 0) return 1.0;

		double numMatches = 0;
		for(unsigned int i = 0; i < mask.length(); i++) {
			if(mask[i] == srhs.mask[i]) numMatches++;
		}

		return numMatches / ((double) mask.length());
	}

protected:
	string mask;
};

class TEXT_Response : public Response {
public:
	TEXT_Response(const XMLNode& node) {
		questionID = node.AttribValueByNameInt("q_id");
	}

	ResponseType GetType() const {
		return R_TEXT;
	}

	double ComparisonScore(const Response& rhs) const {
		if(rhs.GetType() != R_TEXT) throw string("Invalid comparison");
		return 1.0;
	}
};

inline Response* LoadResponseFromXML(const XMLNode& node) {
	string type = node.AttribValueByName("type");
	if(type == "CAO") {
		return new CAO_Response(node);
	} else if(type == "MC") {
		return new MC_Response(node);
	} else if(type=="SCHED") {
		return new SCHED_Response(node);
	} else if(type=="TEXT") {
		return new TEXT_Response(node);
	} else {
		throw string("Unknown question type - " + type);
	}
}


#endif
