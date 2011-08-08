//StringParser.h
//Defines helpful functions for parsing a string

#ifndef STRINGPARSER_H
#define STRINGPARSER_H

#include <string>

using namespace std;

const string WhitespaceChars=" \t\n";

class StringParser {
public:
	StringParser(string s="") : data(s) {
	}

	bool EndOfString() const {
		return data=="";
	}

	string GetCurrentWord(string delimiters=WhitespaceChars,bool returnDelimiter=false) const {
		string::size_type pos=data.find_first_of(delimiters);
		if(pos==string::npos)
			return data;
		else
			if(returnDelimiter)
				return data.substr(0,pos+1);
			else
				return data.substr(0,pos);
	}

	string ReadWord(string delimiters=WhitespaceChars,bool returnDelimiter=false) {
		string::size_type pos=data.find_first_of(delimiters);
		if(pos==string::npos) {
			string ret=data;
			data="";
			return ret;
		} else {
			string ret;
			if(returnDelimiter)
				ret=data.substr(0,pos+1);
			else
				ret=data.substr(0,pos);
			pos=data.find_first_not_of(delimiters,pos);
			if(pos==string::npos)
				data="";
			else
				data=data.substr(pos);
			return ret;
		}
	}

	void EatSpace(string chars=WhitespaceChars) {
		string::size_type pos=data.find_first_not_of(chars);
		if(pos==string::npos)
			data="";
		else
			data=data.substr(pos);
	}

	string ReadUntil(string chars) {
		string::size_type pos=data.find_first_of(chars);
		string ret;
		if(pos==string::npos) {
			ret=data;
			data="";
			return ret;
		} else {
			ret=data.substr(0,pos);
			data=data.substr(pos);
			return ret;
		}
	}

	string ReadUntilWord(string word) {
		string::size_type pos=data.find(word);
		string ret;
		if(pos==string::npos) {
			throw string(word + " not found in " + data);
		} else {
			ret=data.substr(0,pos);
			data=data.substr(pos);
			return ret;
		}
	}

	string ReadString() {
		string::size_type firstQuote=data.find_first_of('"');
		string::size_type secondQuote=data.find_first_of('"',firstQuote+1);
		string ret=data.substr(firstQuote+1,secondQuote-firstQuote-1);
		data=data.substr(secondQuote+1);
		return ret;
	}

	char LeadingCharacter(int i=0) {
		if(data=="")
			throw string("EndOfString is true in LeadingCharacter");
		else
			return data[i];
	}


	char ReadChar() {
		char ret=LeadingCharacter();
		data=data.substr(1);
		return ret;
	}

protected:
	string data;
};


#endif

