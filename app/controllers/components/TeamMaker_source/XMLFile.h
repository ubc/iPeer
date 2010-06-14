//XMLFile.h
//Defines code for reading a basic XML file

#ifndef XMLFILE_H
#define XMLFILE_H

#include <fstream>
#include <iostream>
#include <string>
#include <vector>
#include <stack>
#include "StringParser.h"
#include "Globals.h"

using namespace std;

class XMLAttrib {
public:
	XMLAttrib(string _name) {
		name=_name;
		valueEmpty=true;
	}
	XMLAttrib(string _name,string _value) {
		name=_name;
		value=_value;
		valueEmpty=false;
	}
	string name;
	string value;
	bool valueEmpty;
};

class XMLNode {
public:
	string name;

	vector<XMLAttrib> attributes;
	vector<XMLNode> childNodes;

	bool AttribExists(string n) const {
		for(unsigned int i=0;i<attributes.size();i++)
			if(attributes[i].name==n) return true;
		return false;
	}

	const XMLAttrib& AttribByName(string n) const {
		for(unsigned int i=0;i<attributes.size();i++)
			if(attributes[i].name==n) return attributes[i];
		throw string(n + " attribute not found");
	}

	bool AttribValueByNameBool(string n,bool _default=false) const {
		string val=AttribValueByName(n,"");
		if(val=="")
			return false;
		else
			if(val=="true" || val=="1")
				return true;
			else if(val=="false" || val=="0")
				return false;
			else
				throw string("Unknown bool attribute value - ") + val;
		return false;
	}

	string AttribValueByName(string n,string _default="") const {
		for(unsigned int i=0;i<attributes.size();i++)
			if(attributes[i].name==n)
				if(attributes[i].valueEmpty)
					return _default;
				else
					return attributes[i].value;
		return _default;
	}

	int AttribValueByNameInt(string n,int _default=0) const {
		string val=AttribValueByName(n,"");
		if(val=="")
			return _default;
		else
			return atoi(val.c_str());
	}

	float AttribValueByNameFloat(string n,float _default=0) const {
		string val=AttribValueByName(n,"");
		if(val=="")
			return _default;
		else
			return atof(val.c_str());
	}


	bool ChildExists(string n) const {
		for(unsigned int i=0;i<childNodes.size();i++)
			if(childNodes[i].name==n) return true;
		return false;
	}

	vector<XMLNode> NodesByName(string n) const {
		vector<XMLNode> ret;
		for(unsigned int i=0;i<childNodes.size();i++)
			if(childNodes[i].name==n) ret.push_back(childNodes[i]);
		return ret;
	}

	const XMLNode& ChildByName(string n) const {
		for(unsigned int i=0;i<childNodes.size();i++)
			if(childNodes[i].name==n) return childNodes[i];
		throw string(n + " child node not found");
	}

	string ToString(int tabLevel=0) const {
		string ret=RepeatString("\t",tabLevel);
		ret+="<";
		ret+=name;
		for(unsigned int i=0;i<attributes.size();i++) {
			ret+=" ";
			ret+=attributes[i].name + "=";
			ret+=Quoted(attributes[i].value);
		}

		if(childNodes.size()==0) {
			ret+="/>\n";
		} else {
			ret+=">\n";
			for(unsigned int i=0;i<childNodes.size();i++) {
				ret+=childNodes[i].ToString(tabLevel+1);
			}
			ret+=RepeatString("\t",tabLevel)+"</";
			ret+=name;
			ret+=">\n";
		}
		return ret;
	}

};

class XMLFile {
public:
	XMLFile(string filename="") {
		if(filename!="") {
			Load(filename);
		}
	}

	void Load(string filename) {
		string data=LoadFile(filename); //file contents
		rootNode=ParseString(data);
		return;
	}

	XMLNode rootNode;

protected:
	XMLNode ParseString(string s) {
		StringParser parser(s);
		XMLNode ret;

		parser.ReadUntil("<");

		while(!parser.EndOfString()) {
			XMLNode node;	//the node we're parsing

			if(parser.LeadingCharacter()!='<')	//make sure we're in the right spot
				throw string("Sanity check fails in XML load");


			parser.ReadChar();	//eat the <
			parser.EatSpace();	//eat any space between < and the tag name
			node.name=parser.ReadUntil(WhitespaceChars + "/>"); //get the tag name
			parser.EatSpace();	//eat space between tag name and first attribute

			
			while(true) {		//loop until we find an end-of-tag marker
				//we found the end of a <foo/> tag, we're done
				if(parser.LeadingCharacter(0)=='/' && parser.LeadingCharacter(1)=='>') {
					ret.childNodes.push_back(node);
					node=XMLNode();
					parser.ReadChar();
					parser.ReadChar();
					break;
				}

				//same case as above, except a <?foo bar=blah ?> node
				if(parser.LeadingCharacter(0)=='?' && parser.LeadingCharacter(1)=='>') {
					ret.childNodes.push_back(node);
					node=XMLNode();
					parser.ReadChar();
					parser.ReadChar();
					break;
				}

				//we found the first > in a <tag> ... </tag>
				if(parser.LeadingCharacter()=='>') {
					parser.ReadChar();					//eat the <
					//There might be </foo> inside that doesn't actually match ours
					//so we count any opening <foo that would create a false match
					
					string content=parser.ReadUntilWord("</" + node.name + ">");	//read stuff between the > and the <
					node.childNodes=ParseString(content).childNodes; //recursively parse inside
					ret.childNodes.push_back(node);
					node=XMLNode();
					string temp=parser.ReadUntil(">");	//eat until the closing >
					parser.ReadChar();					//eat the closing >
					parser.EatSpace();					//advance to the next tag
					break;
				}

				//read the attribute name
				string attribName=parser.ReadUntil(WhitespaceChars + "=");
				//eat possible space for the <foo bar ="hi"> case
				parser.EatSpace();
				if(parser.LeadingCharacter() == '=') { //there's a value for the attribute
					parser.ReadChar(); //eat the =
					parser.EatSpace(); //eat any trailing space after =
					string attribValue=parser.ReadString(); //read the attribute value
					node.attributes.push_back(XMLAttrib(attribName,attribValue));					
				} else {
					node.attributes.push_back(XMLAttrib(attribName));
				}
				parser.EatSpace(); //eat any trailing space after value/name
			}
			parser.EatSpace();
		}
		return ret;
	}

};


#endif

