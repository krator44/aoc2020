#include <cstdlib>
#include <cstdio>
#include <cstring>


int main(int argc, char **argv);


int main(int argc, char **argv) {
  long subject = 7;
  long count = 0;
  long result = 1;
  int door_public = 14082811;
  int card_public = 5249543;
//  int door_public = 17807724;
//  int card_public = 5764801;
  
  int door_loop, card_loop;
  count = 0;
  result = 1;
  for(;;) {
    if(result == door_public) {
      printf("door loop size %ld\n", count);
      door_loop = count;
      break;
    }
    result *= subject;
    result %= 20201227;
    //printf("%ld result\n", result);
    count++;
  }
  count = 0;
  result = 1;
  for(;;) {
    //if(result == 14082811) {
    if(result == card_public) {
      printf("card loop size %ld\n", count);
      card_loop = count;
      break;
    }
    result *= subject;
    result %= 20201227;
    //printf("%ld result\n", result);
    count++;
  }
  result = 1;
  subject = door_public;
  for(int i=0;i < card_loop;i++) {
    //if(result == 14082811) {
    result *= subject;
    result %= 20201227;
    //printf("%ld result\n", result);
    count++;
  }
  printf("%ld encryption code\n", result);
  result = 1;
  subject = card_public;
  for(int i=0;i < door_loop;i++) {
    //if(result == 14082811) {
    result *= subject;
    result %= 20201227;
    //printf("%ld result\n", result);
    count++;
  }
  printf("%ld encryption code\n", result);
}



