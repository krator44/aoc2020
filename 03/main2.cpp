#include <cstdlib>
#include <cstdio>
#include <cstring>


int check_path(int dx, int dy);


int main(int argc, char **argv) {
  int path[5];
  for (int i = 0; i < 5; i++) {
    path[i] = 1;
  }
  path[0] = check_path(1, 1);
  path[1] = check_path(3, 1);
  path[2] = check_path(5, 1);
  path[3] = check_path(7, 1);
  path[4] = check_path(1, 2);

  long total = 1;
  for (int i = 0; i < 5; i++) {
    total *= path[i];
  }

  printf("%d, %d, %d, %d, %d\n", path[0], path[1], path[2],
    path[3], path[4]);
  printf("total = %ld\n", total);
}

int check_path(int dx, int dy) {
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
  printf("%s", ss);
  x = dx;
  for(;;) {
    if (feof(ff)) {
      break;
    }
    fgets(ss, 2048, ff);
    if (feof(ff)) {
      break;
    }
    if (dy > 1) {
      printf("%s", ss);
      if (feof(ff)) {
        break;
      }
      fgets(ss, 2048, ff);
    }
/*    if(strlen(ss) - 1 != 31) {
      printf("ERROR\n");
      exit(0);
    }
*/
    if (ss[x] == '#') {
      ss[x] = 'X';
      tree_count++;
    }
    else if (ss[x] == '.') {
      ss[x] = 'O';
    }
    else { 
      printf ("ERROR\n");
      exit(0);
    }
    x += dx;
    if (x > (width - 1)) {
      x -= width;
    }

    if(x > (width - 1) || x < 0) {
      printf ("ERROR\n");
      exit(0);
    }
    printf("%s", ss);
    //printf("width = %d\n", width);
  }
  printf("tree count = %d\n", tree_count);

  fclose(ff);

  return tree_count;
}




