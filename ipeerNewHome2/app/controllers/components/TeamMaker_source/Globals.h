//Globals.h
//Defines handy helper functions

#ifndef GLOBALS_H
#define GLOBALS_H

#include <iostream>
#include <fstream>
#include <string>
#include <cstring>
#include <cmath>

using namespace std;

string LoadFile(string filename);
bool IsWhitespace(char c);
string RepeatString(string s,int count);
string Quoted(string s);

template <class T> inline T Max(T x,T y) {
	return (x > y) ? x : y;
}

template <class T> inline T Min(T x,T y) {
	return (x < y) ? x : y;
}

template <class T> inline T absv(T x) {
	if(x < 0)
		return -x;
	else
		return x;
}

inline bool IsDigit(char c) {
	return string("+-.0123456789").find(c)!=string::npos;
}

inline bool StartsWith(string s,string t) {
	return t==s.substr(0,t.length());
}

#endif

