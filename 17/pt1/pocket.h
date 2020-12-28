#include <cstdlib>
#include <cstdio>
#include <cstring>

class Pocket;
class Grid;

struct slice_t {
  int min_x, min_y, min_z;
  int max_x, max_y, max_z;
};

class Grid {
  public:
  int max_x, max_y, max_z;

  Grid();
  Grid(int max_xx, int max_yy, int max_zz);
  Grid(const Grid &tt);
  Grid& operator=(const Grid &tt);
  ~Grid();

  void read_input(const char *fn, int init_x, int init_y, int init_z);
  void set_cube(int x, int y, int z, unsigned char n);
  unsigned char get_cube(int x, int y, int z) const;
  void reset_grid();
  int adjacent(int x, int y, int z, unsigned char state) const;
  int count_cubes(unsigned char state) const;
  bool check_bounds(int x, int y, int z) const;

  static void print_coords(int x, int y, int z);
  static void print_cube(unsigned char n);
  static unsigned char decode_char(char ch);

  void print_slice(int z) const;
  void print_view() const;
  slice_t determine_view() const;
  private:
  unsigned char *data;
};

class Pocket {
  public:
  Pocket();
  Pocket(int max_xx, int max_yy, int max_zz);
  
  void iterate();
  
  int max_x, max_y, max_z;
  Grid grid;
};



