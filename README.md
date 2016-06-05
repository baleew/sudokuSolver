# sudokuSolver
(Тестовое задание ддя fl.ru)

PHP Sudoku Solver
=================

A sudoku solver implemented in PHP. It uses a bruteforce back-tracking algorithim.


Changelog
========

**05/06/2016**

* Replaced loop-by check with possibles check which give high performance. Time take to solve 1000 Sudokus: `4.9 seconds`. Thanks to [deathshawdow](https://forums.digitalpoint.com/members/deathshadow.81916/). 


How to Use
==========

The package consists of main class, a solver, `SudokuSolver` as test task. 


##### Using Solver

To use this, you can initialize the SudokuSolver with a long string of numbers where 0 means an empty value. Calling `solve()` on object will try to solve the Sudoku by Backtracking algorithim. If it cannot be solved, `SudokuSolver::NOT_SOLVABLE` is returned.

```php
include "SudokuSolver.class.php";
$sudoku = new SudokuSolver("103000509002109400000704000300502006060000050700803004000401000009205800804000107");
$sudoku->solve();

$sudoku->display();
```

```html
<pre>
1 4 3 | 6 2 8 | 5 7 9 
5 7 2 | 1 3 9 | 4 6 8 
9 8 6 | 7 5 4 | 2 3 1 
------+-------+------
3 9 1 | 5 4 2 | 7 8 6 
4 6 8 | 9 1 7 | 3 5 2 
7 2 5 | 8 6 3 | 9 1 4 
------+-------+------
2 3 7 | 4 8 1 | 6 9 5 
6 1 9 | 2 7 5 | 8 4 3 
8 5 4 | 3 9 6 | 1 2 7 
</pre>
```

