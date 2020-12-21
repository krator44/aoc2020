#include <cstdlib>
#include <cstdio>
#include <cstring>

enum Fields {


int main(int argc, char **argv) {
  FILE *ff;
  int min, max;
  int tree_count = 0;
  char ch;
  char ss[2048];
  int valid_count = 0;
  int c;
  ff = fopen("input", "r");
  for(;;) {
    for (;;) {
      c = fgetc(ff);
      if (c == '\n') {
        // passport done
        break;
      }
    }
  }
  printf("tree count = %d\n", tree_count);
  fclose(ff);
}






