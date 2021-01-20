<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EXCEL TO MYSQL</title>
    <script src="jquery.js"></script>
</head>
<body>
    <center>
    <h1>Import Excel to Mysql</h1>
    <span id="response"></span>
    <form method="POST" id="import_excel_file" enctype="multipart/form-data">
    <table>
        <tr>
        <td  width="25%">Select File</td>
        <td width="50%"><input type="file" name="import_excel"></td>
        <td  width="25%"><button type="submit" id="import" name="submit">Import</button></td>
        </tr>
        </table>
        </form>
    
    </center>
    <section>
        <div class="container">
            <center>
                <div>
                <table class="table-bordered">
                    <thead>
                        <th>ID</th>
                        <th>Question</th>
                        <th>category</th>
                    </thead>
                    <tbody>
                        <?php 
                        
                        include 'config.php';

                        if (isset($_GET['page_no']) && $_GET['page_no']!="") {
                            $page_no = $_GET['page_no'];
                            } else {
                                $page_no = 1;
                                }
                        
                            $total_records_per_page =10;
                            $offset = ($page_no-1) * $total_records_per_page;
                            $previous_page = $page_no - 1;
                            $next_page = $page_no + 1;
                             $adjacents = "2"; 
                        
                            $result_count = mysqli_query($conn,"SELECT COUNT(*) As total_records FROM `tbl_info`");
                            $total_records = mysqli_fetch_array($result_count);
                            $total_records = $total_records['total_records'];
                            $total_no_of_pages = ceil($total_records / $total_records_per_page);
                            $second_last = $total_no_of_pages - 1; // total page minus 1
        
                        $sql = "SELECT * FROM tbl_info LIMIT $offset, $total_records_per_page";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        ?>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?= $row['id']; ?></td>
                                            <td><?= $row['question']; ?></td>
                                            <td><?= $row['category']; ?></td>   
                                        </tr>
                                            <?php } ?>
                                    </tbody>
                    </tbody>
                </table>
                </div>
                <div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC; width:200px;'>
                                <p class="text-muted">Page <?php echo $page_no." of ".$total_no_of_pages; ?></p>
                     </div>
                     <nav aria-label="Page navigation ">
                     <ul class="pagination justify-content-center">
	<?php // if($page_no > 1){ echo "<li><a href='?page_no=1'>First Page</a></li>"; } ?>
    
	<li <?php if($page_no <= 1){ echo "class='page-link disabled'"; } ?>>
	<a <?php if($page_no > 1){ echo "href='?page_no=$previous_page'tabindex='-1' class='page-link'"; } ?>>Previous</a>
	</li>
       
    <?php 
	if ($total_no_of_pages <= 10){  	 
		for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
			if ($counter == $page_no) {
		   echo "<li class='active page-item'><a class='page-link'>$counter</a></li>";	
				}else{
           echo "<li class='page-item'><a href='?page_no=$counter' class='page-link'>$counter</a></li>";
				}
        }
	}
	elseif($total_no_of_pages > 10){
		
	if($page_no <= 4) {			
	 for ($counter = 1; $counter < 8; $counter++){		 
			if ($counter == $page_no) {
		   echo "<li class='active page-item'><a class='page-link'>$counter</a></li>";	
				}else{
           echo "<li class='page-item' ><a href='?page_no=$counter'  class='page-link'>$counter</a></li>";
				}
        }
		echo "<li><a>...</a></li>";
		echo "<li class='page-item'><a href='?page_no=$second_last' class='page-link'>$second_last</a></li>";
		echo "<li class='page-item'><a href='?page_no=$total_no_of_pages' class='page-link'>$total_no_of_pages</a></li>";
		}

	 elseif($page_no > 4 && $page_no < $total_no_of_pages - 4) {		 
		echo "<li><a href='?page_no=1'>1</a></li>";
		echo "<li><a href='?page_no=2'>2</a></li>";
        echo "<li><a>...</a></li>";
        for ($counter = $page_no - $adjacents; $counter <= $page_no + $adjacents; $counter++) {			
           if ($counter == $page_no) {
		   echo "<li class='active page-item'><a>$counter</a></li>";	
				}else{
           echo "<li class='page-item'><a class='page-link' href='?page_no=$counter'>$counter</a></li>";
				}                  
       }
       echo "<li><a>...</a></li>";
	   echo "<li class='page-item'><a class='page-link' href='?page_no=$second_last'>$second_last</a></li>";
	   echo "<li class='page-item'><a class='page-link' href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";      
            }
		
		else {
        echo "<li><a href='?page_no=1'>1</a></li>";
		echo "<li><a href='?page_no=2'>2</a></li>";
        echo "<li><a>...</a></li>";

        for ($counter = $total_no_of_pages - 6; $counter <= $total_no_of_pages; $counter++) {
          if ($counter == $page_no) {
		   echo "<li class='active page-item'><a class='page-link'>$counter</a></li>";	
				}else{
           echo "<li class='page-item'><a href='?page_no=$counter' class='page-link'>$counter</a></li>";
				}                   
                }
            }
	}
?>
    
	<li <?php if($page_no >= $total_no_of_pages){ echo "class='disabled page-link'"; } ?>>
	<a <?php if($page_no < $total_no_of_pages) { echo "href='?page_no=$next_page' class='page-link'"; } ?>>Next</a>
	</li>
    <?php if($page_no < $total_no_of_pages){
		echo "<li class='page-item'><a href='?page_no=$total_no_of_pages' class='page-link'>Last &rsaquo;&rsaquo;</a></li>";
		} ?>
</ul> 
    </nav>     
            </center>
        </div>
    </section>
    <script>
	
       $(document).ready(function () {

        $('#import_excel_file').on('submit', function (e) {

        e.preventDefault();
        
        $.ajax({
            type: 'POST',
            url: 'execute.php',
            data: new FormData(this),
            contentType:false,
            cache:false,
            processData:false,
            beforeSend: function(){
                $('#import').attr('disable','disabled')
                $('#import').html('Please wait...')
            },
            success: function (data) {
                $('#response').html(data)
                $('#import_excel_file')[0].reset()
                $('#import').attr('disable',false)
                $('#import').html('Import')
            }
        });

        });

        });
    
    </script>
</body>
</html>