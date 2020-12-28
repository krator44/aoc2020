#include <cstdlib>
#include <cstdio>
#include <cstring>

#include "pocket.h"

int main(int argc, char **argv);

int main(int argc, char **argv) {
  if (argc != 2) {
    printf("usage: %s input\n", argv[0]);
    exit(0);
  }

  char *fn;
  fn = argv[1];

  Pocket pocket(40,40,40, 40);
  pocket.grid.read_input(fn, 10, 10, 10, 10);

  printf("initial state\n");
  pocket.grid.print_view();

  for(int i = 0; i < 6; i++) {
    pocket.iterate();
    printf("cycle %d\n", i+1);
    pocket.grid.print_view();
    printf("\n");
  }

  int count = pocket.grid.count_cubes(1);
  printf("%d cubes active\n", count);

  return 0;
}





