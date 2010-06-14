#include "Globals.h"

string LoadFile(string filename) {
	const unsigned int BufferSize=1024;
	ifstream fileStream(filename.c_str());
	char buffer[BufferSize];

	string ret="";
	while(fileStream.good()) {
		fileStream.get(buffer,BufferSize,'\0');
		if(strlen(buffer)+1<BufferSize) {
			ret += buffer;
			return ret;
		} else {
			ret += buffer;
		}
	}
	throw string("Shouldn't be here in LoadFile");	
}

bool IsWhitespace(char c) {
	return (c==' ') || (c==10) || (c==13) || (c=='\t');
}

string RepeatString(string s,int count) {
	string ret="";
	for(int i=0;i<count;i++)
		ret+=s;
	return ret;
}

string Quoted(string s) {
	return string("\"") + s + "\"";
}
