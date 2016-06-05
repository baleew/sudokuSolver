<?php
class SudokuSolver {
		protected $grid = [];
		protected $emptySymbol;
protected $_iSudoku = array();
protected $_oSudoku = array();
protected $_oSudoku90;
protected $_stack;
protected $_cols;
protected $_rows;
protected $_blocks;
protected $_compare;
protected $_error_msg ="задача не решается по таким-то и таким-то причинам:<br>";

		public static function parseString($str, $emptySymbol = '0'){
			$grid = str_split($str);
			foreach($grid as &$v){
			if($v == $emptySymbol){$v = 0;}
				else{$v = (int)$v;}
			}
			return $grid;
		}
 
	public function __construct($str, $emptySymbol = '0') {$len=strlen($str);
	if($len !== 81){$str=substr(str_pad($str, 81, $emptySymbol),0, 81);
echo("<h4>Error sudoku::</h4>Введенная строка содержит: $len символов, что противоречит правилам ввода Sudoku,
	<br>но тем не менее, она так же может иметь правильное решение.<br>
	Введенная строка будет дополнена пустыми значениями и проверена на возможность решения.<br>"
	);
var_dump($str);			
//throw new \Exception('Error sudoku');
		}
$_arr=str_split($str, 9);
array_walk($_arr, function(&$v){$v = str_split($v);});
$this->_iSudoku = $this->_oSudoku = $this->_rows = $_arr;
$this->_stack = new Stack();
$this->_data=array_pad(array(),81,0);
$this->raw=$str;
		$this->grid = static::parseString($str, $emptySymbol);
		$this->emptySymbol = $emptySymbol;
$this->_compare = range(1, 9);
$this->_constuctBlock();
//var_dump($this->getXYZ(0));//$this->getXYZ(0)
	}
 
    protected function _constuctBlock() {$_cols;
        for ( $x = 0; $x < 9; $x++ ){
            $_cols[$x] = array();
            for ( $y = 0; $y < 9; $y++ ) {
                $_cols[$x][$y] = &$this->_rows[$y][$x];
            }
        }
        $this->_cols=$this->_oSudoku90=$_cols;
        // create '_blocks'
        for ( $_x = 0; $_x < 3; $_x++ ) {
            $this->_blocks[$_x] = array();
            for ( $_y = 0; $_y < 3; $_y++ ) {
                $this->_blocks[$_x][$_y] = array();
                $gridX = $_x * 3;
                for ( $cellX = 0; $cellX < 3; $cellX++ ) {
                    $gridY = $_y * 3;
                    for ( $cellY = 0; $cellY <3; $cellY++ ) {
                        $this->_blocks[$_x][$_y][] = &$this->_oSudoku[$gridX][$gridY++];
                    }
                    $gridX++;
                }
            }
        }
    }

public function missingColumn($x) {
return array_diff($this->_compare, $this->_oSudoku[$x]);
}

public function missingRow($y) {
return array_diff($this->_compare, $this->_cols[$y]);
}

public function missingBlock($x,$y) {
return array_diff($this->_compare, $this->_blocks[$x][$y]);
}

/* An intersect of all the possibles finds the possibles obeying rules of sudoku */
public function possibles($x, $y){
return array_intersect(
    $this->missingBlock((int)$x / 3, (int)$y / 3),
    $this->missingColumn($x),
    $this->missingRow($y)
);
}
public function checkValid($v, $x, $y) {
return in_array($v, array_intersect(
    $this->missingBlock((int)$x / 3, (int)$y / 3),
    $this->missingColumn($x),
    $this->missingRow($y)
));
}

public function getXYZ($i,$v=0){$x=$i%9;$y=$i/9|0;$_x=$x/3|0;$_y=$y/3|0;
if(!$v)return false;
$v=$this->grid[$i];$r=$this->_rows[$y];$c=$this->_cols[$x];$b=$this->_blocks[$_x][$_y];
$arr =array('r'=>$r,'c'=>$c, 'b'=>$b);
$arr=array_map(function($v){return array_filter(array_count_values($v),function($v){return $v>1;});},$arr); //array_merge($r,$c,$b);
$arrA=$arr['r']+$arr['c']+$arr['b'];
if($e=array_key_exists($v, $arrA))return  $arrA;

}

protected function checkValidity2($v,$i){
$x=$i%9;$y=$i/9|0;$x0=($x/3|0)*3;$y0=($y/3|0)*3; $add=(1<<$v);
	for ($k=0; $k<9; $k++) {
		$this->_data[$x+$k*9]|=$add;
		$this->_data[$k+$y*9]|=$add;      
		$this->_data[$x0+($k%3)+9*($y0+($k/3|0))]|=$add;
	}
	$_v= 1023-(1<<$v);
	if(($_v&1022)!=1022 && ($_v&1)!=0){$this->_data[$i]=$_v;
		return $_v;}
	return false;
}

		public function solve(){
	try{$this->placeNumber(0); return false;}catch(\Exception $e){	//var_dump($this->data);
return true;}
		}
		protected function placeNumber($i){
			if($i == 81){throw new \Exception('Finish');}
			$_v=$this->grid[$i];$x=$i%9; $y=$i/9|0;
		
			if($_v){
	if($this->checkDuplicate($_v, $i, true)){}
				$this->placeNumber($i+1);return;}
			//$range=range(1,9);
			//for($n = 1; $n <= 9; $n++)
			foreach(range(1,9) as $n){
				if($this->checkValidity($n, $i)){ //
					$this->grid[$i] = $n;
					$this->placeNumber($i+1);
					$this->grid[$i] = 0;
				}
			}
		}



protected function checkDuplicate($v, $i, $force=NULL){
if($force && $v && $this->getXYZ($i,$v)){
$this->_error_msg.="duplicate data value.<br>";
throw new \Exception('Error Sudoku');}
}

protected function checkValidity($v, $_i, $force=NULL){$x=$_i%9;$y=$_i/9|0; 
for($i = 0; $i < 9; $i++){//validity rows & cols;
	$_x=$i*9+$x; $_y=$y*9+$i;
//if($force && ($_x==$_i || $_y==$_i))continue;
		if(($this->grid[$_x] == $v)||($this->grid[$_y] == $v)){return false;}
		}
		$_x = (int) ((int)($x/3)*3);
		$_y = (int) ((int)($y/3)*3);

		for($i = $_y; $i<$_y+3;$i++){
			for($j = $_x; $j<$_x+3;$j++){
				if($this->grid[$i*9+$j] == $v){return false;}
			}
		}
		return true;
	}
 
		public function display($force) {
			$str = ''; $error="";
			for($i = 0; $i<9; $i++){
				for($j = 0; $j<9;$j++){
					$str .= $this->grid[$i*9+$j];
					$str .= " ";
					if($j == 2 || $j == 5){$str .= "| ";}
				}
				$str .= PHP_EOL;
				if($i == 2 || $i == 5){
					$str .=  "------+-------+------".PHP_EOL;
				}
			}
if(preg_match("/0/",$str)){$error='style="color:red;"';
die("<h4><font color='#fff000' style='background:red;'>SUDOKU ERROR!!!</font></h4><pre $error>$str</pre><br>
	$this->_error_msg") ;}
if($force)die("<pre>$str</pre>");
	return $this->raw;
}
 
		public function __toString() {
			foreach ($this->grid as &$item){
				if($item == 0){
					$item = $this->emptySymbol;
				}
			}
			return implode('', $this->grid);
		}

}; //end SudokuSolver


//$solver = new SudokuSolver('009170000020600001800200000200006053000051009005040080040000700006000320700003900');
//$solver->solve();
//<pre><?$solver->display();exit;</pre>
