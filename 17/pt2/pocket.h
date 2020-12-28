#include <cstdlib>
#include <cstdio>
#include <cstring>

class Pocket;
class Grid;

struct slice_t {
  int min_x, min_y, min_z, min_w;
  int max_x, max_y, max_z, max_w;
};

class Grid {
  public:
  int max_x, max_y, max_z, max_w;

  Grid();
  Grid(int max_xx, int max_yy, int max_zz, int max_ww);
  Grid(const Grid &tt);
  Grid& operator=(const Grid &tt);
  ~Grid();

  void read_input(const char *fn,
    int init_x, int init_y, int init_z, int init_w);
  void set_cube(int x, int y, int z, int w, unsigned char n);
  unsigned char get_cube(int x, int y, int z, int w) const;
  void reset_grid();
  int adjacent(int x, int y, int z, int w, unsigned char state) const;
  int count_cubes(unsigned char state) const;
  bool check_bounds(int x, int y, int z, int w) const;

  static void print_coords(int x, int y, int z, int w);
  static void print_cube(unsigned char n);
  static unsigned char decode_char(char ch);

  void print_bounds() const;
  void print_slice(int z, int w) const;
  void print_view() const;
  slice_t determine_view() const;
  private:
  unsigned char *data;
};

class Pocket {
  public:
  Pocket();
  Pocket(int max_xx, int max_yy, int max_zz, int max_ww);
  
  void iterate();
  void print_bounds() const;
  
  int max_x, max_y, max_z, max_w;
  Grid grid;
};



