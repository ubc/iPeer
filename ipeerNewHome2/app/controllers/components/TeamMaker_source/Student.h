#ifndef STUDENT_H
#define STUDENT_H

class Student {
public:
	Student(const XMLNode& node) {
		username = node.AttribValueByName("username");
		for(unsigned int i = 0; i < node.childNodes.size(); i++)
			responses.push_back(LoadResponseFromXML(node.childNodes[i]));
	}

	Response* ResponseByID(int id) {
		for(unsigned int i = 0; i < responses.size(); i++)
			if(responses[i]->GetQuestionID() == id) return responses[i];
		throw string("Couldn't find response");
	}

	string username;
	vector<Response*> responses;
};

#endif
