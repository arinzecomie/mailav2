<?php 
require_once"config/liblery.php";

if(isset($_GET['check'])){
     $checkid = $_GET['checkid'];
      $check = $_GET['check'] ? 0:1;
       $data = ["interset" => $check ];
            $where = ['id' => $checkid];
            $db->update('user', $data, $where);
    if ($db->success()) {
        toast("User check successfully!");
    } else {
        toast("Failed to check user!"); 
         } 
      }


$users = $db->select('mail_acc');
$checker = ['uncheck-icon.jpg','check-icon.png'];

if(isset($_GET['del'])){
      $del = $_GET['del'];
    $db->delete("user", ['id' => $del]);
    if ($db->success()) {
        toast("User deleted successfully!");
    } else {
        toast("Failed to delete user!"); 
         } 
      }
?>
<style>
  .img {
    height: 54px;
    border-radius: 20px;
    border: solid #0dcaf0;
}
</style>
<!DOCTYPE html>
<html lang="en">                                  
<head>
  <title>Email Account Sender</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
</head>
<body>

<div class="container mt-3">
  <h2>Email Account Sender </h2>
  
 
  <div class="row">
    <div class="col">
      <br>
  <div class="card">
  <div class="card-header"><p>List of Emails </p> </div>
  <div class="card-body">
  <table id="example" class="table table-striped" style="width:100%">
    <thead>
      <tr>
      <th>Id </th>
      <th>Logo </th>
       <th>Set From </th>
        <th>Mail type</th>
        <th>Reply to</th>
        <th>URL</th>
        <th>Status</th>
        <th>Date</th>
        
        <th style="text-align: center;">Action</th>
      </tr>
    </thead>
  
    <tbody>
      <?php foreach ($users as $user) { ?>
      <tr>
      <td><?= $user['id']  ?></td>
        <td><img src="./upload/<?= $user['img']?>" class="img" ></td>
       <td><?= $user['setfrom']  ?></td>
       <td><?= $user['mailtype']  ?></td>
       <td><?= $user['replyto']  ?></td>
       <td><?= $user['url']  ?></td>
        <td><img src="<?= $checker[$user['status']]  ?>" class="img" ></td>
         <td><?= $user['date']  ?></td>
        <td> <a href="?del=<?= $user['id']  ?>"><button type="button" onclick="confirm('Are you sure you want to proceed?');" class="btn btn-danger">Delete</button></a></td>
      </tr> 
  
      <?php } ?>
    </tbody>
  </table>
  </div>
  <div class="card-footer">Good luck </div>
</div>
</div>
</div>
</div>
<br>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
  new DataTable('#example');
</script>
</body>
</html>