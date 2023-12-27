#include <iostream>
#include <vector>
#include <string>
#include <cstdlib>
#include <cstdio>
#include "md5.h"
#include "ovo.h"

int main(int argc, char const *argv[])
{
    
    ovo::file f;
    vector<string> s;
    vector<string> fs;

    f.get_files_info(".\\");

    int count;


    for(int i = 0; i < f.num(); i++){
        
        string t = md5file((f.file[i].name).c_str());

        for(count = -1; ++count < s.size(); ){

            if(s[count] == t) break;
        }

        if(count == s.size()){

            s.push_back(t);
            fs.push_back(f.file[i].name);
        }
    }


    std::cout << s.size();

    system("md unique");

    for(int i = 0; i < s.size(); i++){

        string ts = "copy \"";

        ts += fs[i];

        ts += "\" .\\unique";

        std::cout << ts << "\n";

        system(ts.c_str());
    }

    system("pause");

    return 0;

}