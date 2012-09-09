using System;
using System.Collections.Generic;
using System.Text;
using System.IO;

namespace rcsharp
{
    class Factorial
    {
        static void Main(string[] args)        {
                using (StreamReader sr = new StreamReader("C:/entrada.txt"))
                using (StreamWriter sw = new StreamWriter("C:/salidaCsharp.out"))
                {
                    int n = int.Parse(sr.ReadLine());
                    for (int i = 0; i < n; i++)
                    {
                        int numero = int.Parse(sr.ReadLine());
                        int fact = 1;
                        for (int j = 1; j < numero; j++)
                        {
                            fact = fact * j;
                        }
                        sw.WriteLine("Caso #" + (i + 1) + ": " + fact);
                    }
                }
        }
    }
}
