#include <cstdlib>
#include <cstdio>
#include <cstring>




int main(int argc, char **argv) {
  FILE *ff;
  int min, max;
  int tree_count = 0;
  char ch;
  char ss[2048];
  int valid_count = 0;
  ff = fopen("input", "r");
  int width = 31;
  int x = 0;
  fgets(ss, 2048, ff);
  width = strlen(ss) - 1;
  printf("width = %d\n", width);
  x = 3;
  for(;;) {
    if (feof(ff)) {
      break;
    }
    fgets(ss, 2048, ff);
/*    if(strlen(ss) - 1 != 31) {
      printf("ERROR\n");
      exit(0);
    }
*/
    if (ss[x] == '#') {
      ss[x] = 'X';
      tree_count++;
    }
    else {
      ss[x] = 'O';
    }
    x += 3;
    if (x > (width - 1)) {
      x -= width;
    }
    printf("%s", ss);
    //printf("width = %d\n", width);
  }
  printf("tree count = %d\n", tree_count);

  fclose(ff);
}






