#if 1
 
#include <io.h>  
#include <fstream>  
#include <string>  
#include <vector>  
#include <iostream>  
#include <stdlib.h>
#include<algorithm>
#include <cstring>
 using namespace std;  
  
  
//获取所有的文件名  
void GetAllFiles( string path, vector<string>& files)    
{    
  
    long   hFile   =   0;    
    //文件信息    
    struct _finddata_t fileinfo;    
    string p;    
    if((hFile = _findfirst(p.assign(path).append("\\*").c_str(),&fileinfo)) !=  -1)    
    {    
        do    
        {     
            if((fileinfo.attrib &  _A_SUBDIR))    
            {    
                if(strcmp(fileinfo.name,".") != 0  &&  strcmp(fileinfo.name,"..") != 0)    
                {  
                    files.push_back(p.assign(path).append("\\").append(fileinfo.name) );  
                    GetAllFiles( p.assign(path).append("\\").append(fileinfo.name), files );   
                }  
            }    
            else    
            {    
                files.push_back(p.assign(path).append("\\").append(fileinfo.name) );    
            }   
  
        }while(_findnext(hFile, &fileinfo)  == 0);    
  
        _findclose(hFile);   
    }   
  
}    
  
//获取特定格式的文件名  
void GetAllFormatFiles( string path, vector<string>& files,string format)    
{    
    //文件句柄    
    long   hFile   =   0;    
    //文件信息    
    struct _finddata_t fileinfo;    
    string p;    
    if((hFile = _findfirst(p.assign(path).append("\\*" + format).c_str(),&fileinfo)) !=  -1)    
    {    
        do    
        {      
            if((fileinfo.attrib &  _A_SUBDIR))    
            {    
                if(strcmp(fileinfo.name,".") != 0  &&  strcmp(fileinfo.name,"..") != 0 && !strstr(fileinfo.name,".txt")&& !strstr(fileinfo.name,".cpp")&& !strstr(fileinfo.name,".exe")&& !strstr(fileinfo.name,".bat")&& !strstr(fileinfo.name,".bat"))    
                {  
                	cout << fileinfo.name << endl;
                    //files.push_back(p.assign(path).append("\\").append(fileinfo.name) );  
                    GetAllFormatFiles( p.assign(path).append("\\").append(fileinfo.name), files,format);   
                }  
            }    
            else    
            {    
                files.push_back(p.assign(path).append("\\").append(fileinfo.name) );    
            }    
        }while(_findnext(hFile, &fileinfo)  == 0);    
  
        _findclose(hFile);   
    }   
}   
  
// 该函数有两个参数，第一个为路径字符串(string类型，最好为绝对路径)；  
// 第二个参数为文件夹与文件名称存储变量(vector类型,引用传递)。  
// 在主函数中调用格式(并将结果保存在文件"AllFiles.txt"中，第一行为总数)：  
  
int main()  
{  
    string filePath = ".\\";    
    vector<string> files;    
    char * distAll = "AllFiles.txt";  
  
    //读取所有的文件，包括子文件的文件  
    //GetAllFiles(filePath, files);  


    //读取所有格式为jpg的文件  
    string format = "";  
    GetAllFormatFiles(filePath, files,format);  
    ofstream ofn(distAll);  
    int size = files.size();   
    cout<<size<<endl;

    for(int i=1;i<size;i++)
    {
    	ofn << "![][" << i << "]" << endl;
    }


    ofn << endl << endl; 



    string s = _pgmptr;
    replace(s.begin(),s.end(),'\\','/');



    for (int i = 1;i<size;i++)    
    {    

    	replace( files[i-1].begin(), files[i-1].end(),'\\','/');

        ofn << "[" << i << "]: " << "https://yimian-photo-1256134406.cos.ap-shanghai.myqcloud.com" << s.substr(s.find("yimian-image")+12,s.find("order.exe")-(s.find("yimian-image")+12)) << files[i-1].substr(3) << endl;
    	/*
        ofn<<files[i]<<endl;   
        cout<< files[i] << endl;  
        */
    }  
    ofn.close();  


    system("pause");
    return 0;  
}  
 
 
#endif