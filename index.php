<html>
<head>
<title></title>
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<?php
if ( ! function_exists( 'array_key_last' ) ) {
    /**
     * Polyfill for array_key_last() function added in PHP 7.3.
     *
     * Get the last key of the given array without affecting
     * the internal array pointer.
     *
     * @param array $array An array
     *
     * @return mixed The last key of array if the array is not empty; NULL otherwise.
     */
    function array_key_last( $array ) {
        $key = NULL;

        if ( is_array( $array ) ) {

            end( $array );
            $key = key( $array );
        }

        return $key;
    }
}
include('db.php');
if (isset($_GET['page_no']) && $_GET['page_no']!="") {
	$page_no = $_GET['page_no'];
} 
else {
	$page_no = 1;
}
//write sql query that want to execute
$sql="SELECT * FROM table ";
//write record per page
$total_records_per_page = 5;
$offset = ($page_no-1) * $total_records_per_page;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$adjacents = "2"; 
$result_count = mysqli_query($con,$sql);
$total_records1 = mysqli_fetch_array($result_count);
$total_records=mysqli_num_rows($result_count);
$fieldcount=mysqli_num_fields($result_count);
if($total_records==0){
	echo"<h3 style='font-color:red;'><center>No Recodrs To View<center><h3>";
}
else{
	echo "
		<div class='' style='width:; margin: auto;'>
		<table style='' class='table table-striped table-bordered col-lg-9 '>
		<thead>";
		$fieldinfo=mysqli_fetch_fields($result_count);	
		$total_no_of_pages = ceil($total_records / $total_records_per_page);
		$second_last = $total_no_of_pages - 1; // total page minus 1
		$result2 = mysqli_query($con,$sql."LIMIT $offset, $total_records_per_page");
		foreach ($fieldinfo as $val) {
			echo "<th style='width:20px;'>$val->name</th>";	
			}  
		echo"
		</thead>
		<tbody>";
		foreach($result2->fetch_all(MYSQLI_ASSOC) as $row ) {
			$lastkey = array_key_last ($row);
			foreach($row as $key  => $value) {
				echo '<td>' . $value . '</td>';
					if($key==$lastkey){
						echo"<tr></tr>";
					}
				
			}
		}
	mysqli_close($con);
    ?>
</tbody>
</table>
<div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
<strong>Page <?php echo $page_no." of ".$total_no_of_pages; ?></strong>
<br>
Displaying <?php
			if($page_no==1){
			$first_record=1;
			$last_record=$total_records_per_page;
			echo $first_record.'-'. $last_record." of ".$total_records;	
			}
			else{
				if($page_no!=$total_no_of_pages){
				$first_record=($page_no*$total_records_per_page)-($total_records_per_page-1);
				$last_record=$page_no*$total_records_per_page;
			echo $first_record.'-'. $last_record." of ".$total_records;
				}
				else{
					$first_record=($page_no*$total_records_per_page)-($total_records_per_page-1);
				$last_record=$total_records;
			echo $first_record.'-'. $last_record." of ".$total_records;
					
				}
			}
 ?> results.
</div>

<ul class="pagination">
	    
	<li <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
	<a <?php if($page_no > 1){ echo "href='?page_no=$previous_page'"; } ?>>&laquo;</a>
	</li>
       
    <?php 
	if ($total_no_of_pages <= 10){  	 
		for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
			if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}
        }
	}
	elseif($total_no_of_pages > 10){
		
	if($page_no <= 4) {			
	 for ($counter = 1; $counter < 8; $counter++){		 
			if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}
        }
		echo "<li><a>...</a></li>";
		echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
		echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
		}

	 elseif($page_no > 4 && $page_no < $total_no_of_pages - 4) {		 
		echo "<li><a href='?page_no=1'>1</a></li>";
		echo "<li><a href='?page_no=2'>2</a></li>";
        echo "<li><a>...</a></li>";
        for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {			
           if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}                  
       }
       echo "<li><a>...</a></li>";
	   echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
	   echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";      
            }
		
		else {
        echo "<li><a href='?page_no=1'>1</a></li>";
		echo "<li><a href='?page_no=2'>2</a></li>";
        echo "<li><a>...</a></li>";

        for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
          if ($counter == $page_no) {
		   echo "<li class='active'><a>$counter</a></li>";	
				}else{
           echo "<li><a href='?page_no=$counter'>$counter</a></li>";
				}                   
                }
            }
	}
	
?>
    
	<li <?php 
	if($page_no >= $total_no_of_pages)
	{echo "class='disabled'"; } ?>>
	<a <?php
	 if($page_no < $total_no_of_pages)
	{
		$s=$page_no+1;
		 echo "href='?page_no=$s'";
	}
	?>
>&raquo;
</a>
</li>
<?php
}
?>		
</ul>
<br /><br />
</div>
</body>
</html>