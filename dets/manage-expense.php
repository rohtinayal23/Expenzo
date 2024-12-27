<?php  
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['detsuid']==0)) {
  header('location:logout.php');
} else {

// Code for deletion
if(isset($_GET['delid'])) {
  $rowid=intval($_GET['delid']);
  $query=mysqli_query($con,"delete from tblexpense where ID='$rowid'");
  if($query){
    echo "<script>alert('Record successfully deleted');</script>";
    echo "<script>window.location.href='manage-expense.php'</script>";
  } else {
    echo "<script>alert('Something went wrong. Please try again');</script>";
  }
}

// Code for updating an expense
if (isset($_POST['updateExpense'])) {
  $expenseid = $_POST['expenseid'];
  $expenseItem = $_POST['expenseItem'];
  $expenseCost = $_POST['expenseCost'];
  $expenseDate = $_POST['expenseDate'];

  // Update query
  $query = mysqli_query($con, "UPDATE tblexpense SET ExpenseItem='$expenseItem', ExpenseCost='$expenseCost', ExpenseDate='$expenseDate' WHERE ID='$expenseid'");

  if ($query) {
    echo "<script>alert('Expense updated successfully');</script>";
    echo "<script>window.location.href='manage-expense.php'</script>";
  } else {
    echo "<script>alert('Something went wrong. Please try again');</script>";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daily Expense Tracker || Manage Expense</title>
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/font-awesome.min.css" rel="stylesheet">
  <link href="css/datepicker3.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">

  <!--Custom Font-->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
</head>
<body>
  <?php include_once('includes/header.php');?>
  <?php include_once('includes/sidebar.php');?>
  
  <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
      <ol class="breadcrumb">
        <li><a href="#"><em class="fa fa-home"></em></a></li>
        <li class="active">Expense</li>
      </ol>
    </div><!--/.row-->

    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading">Expense</div>
          <div class="panel-body">
            <p style="font-size:16px; color:red" align="center"> 
              <?php if($msg){ echo $msg; } ?> 
            </p>
            <div class="col-md-12">
              <div class="table-responsive">
                <table class="table table-bordered mg-b-0">
                  <thead>
                    <tr>
                      <th>S.NO</th>
                      <th>Expense Item</th>
                      <th>Expense Cost</th>
                      <th>Expense Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <?php
                    $userid=$_SESSION['detsuid'];
                    $ret=mysqli_query($con,"select * from tblexpense where UserId='$userid'");
                    $cnt=1;
                    while ($row=mysqli_fetch_array($ret)) {
                  ?>
                  <tbody>
                    <tr>
                      <td><?php echo $cnt;?></td>
                      <td><?php echo $row['ExpenseItem'];?></td>
                      <td><?php echo $row['ExpenseCost'];?></td>
                      <td><?php echo $row['ExpenseDate'];?></td>
                      <td>
                        <a href="manage-expense.php?delid=<?php echo $row['ID'];?>" class="deleteExpense">Delete</a> | 
                        <a href="javascript:void(0);" class="editExpense" 
                           data-id="<?php echo $row['ID']; ?>" 
                           data-item="<?php echo $row['ExpenseItem']; ?>" 
                           data-cost="<?php echo $row['ExpenseCost']; ?>" 
                           data-date="<?php echo $row['ExpenseDate']; ?>">
                          Edit
                        </a>
                      </td>
                    </tr>
                    <?php 
                    $cnt=$cnt+1;
                    }?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div><!-- /.panel-->
      </div><!-- /.col-->
      <?php include_once('includes/footer.php');?>
    </div><!-- /.row -->
  </div><!--/.main-->

  <!-- Edit Expense Modal -->
  <div id="editModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Expense</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" id="editExpenseForm">
            <input type="hidden" name="expenseid" id="expenseid">
            <div class="form-group">
              <label for="expenseItem">Expense Item</label>
              <input type="text" name="expenseItem" id="expenseItem" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="expenseCost">Expense Cost</label>
              <input type="number" name="expenseCost" id="expenseCost" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="expenseDate">Expense Date</label>
              <input type="date" name="expenseDate" id="expenseDate" class="form-control" required>
            </div>
            <button type="submit" name="updateExpense" class="btn btn-primary">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="js/jquery-1.11.1.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/custom.js"></script>

  <script>
  $(document).ready(function(){
    // When clicking on the Edit button
    $('a.editExpense').on('click', function(){
        var expenseId = $(this).data('id');
        var expenseItem = $(this).data('item');
        var expenseCost = $(this).data('cost');
        var expenseDate = $(this).data('date');

        // Populate the modal with the existing data
        $('#expenseid').val(expenseId);
        $('#expenseItem').val(expenseItem);
        $('#expenseCost').val(expenseCost);
        $('#expenseDate').val(expenseDate);

        // Open the modal
        $('#editModal').modal('show');
    });
  });
  </script>

</body>
</html>
<?php }  ?>
