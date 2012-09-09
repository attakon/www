#include <stdio.h>
#include <iostream.h>
#include <conio.h>


//using namespace std;
FILE *in = fopen("C:/entrada.txt","r");
FILE *out = fopen("C:/salidaCpp.txt","w");
int main(){    
    int caseN;
	fscanf(in,"%d",&caseN);              //lee una linea como entero lo guarda en la variable caseN
	for(int i=0;i<caseN;i++){
       int numero;
            	fscanf(in,"%d",&numero);
            	int fact=1;
            	for(int j=1;j<numero;j++){
                        fact=fact*j;
                        }
                fprintf(out,"Caso #%d: %d\n",i+1,fact);
            }
}
