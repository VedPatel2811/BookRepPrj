<?php
   require "includes/database.inc.php";

   $search = isset($_GET['search']) ? $_GET['search'] : '';
   $sort = isset($_GET['sort']) ? $_GET['sort'] : 'lastName';

   $sql = "SELECT * FROM Customers";

   if (!empty($search)) {
      $sql .= " WHERE lastName LIKE :search";
   }

   $sql .= " ORDER BY $sort";

   $stmt = $pdo->prepare($sql);

   if (!empty($search)) {
      $stmt->bindValue(':search', "%$search%");
   }

   $stmt->execute();

   $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="Content-Type" content="text/html; 
   charset=UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="">
   <meta name="author" content="">
   <title>Book Template</title>

   <link rel="shortcut icon" href="../../assets/ico/favicon.png">   

   <!-- Bootstrap core CSS -->
   <link href="bootstrap3_bookTheme/dist/css/bootstrap.min.css" rel="stylesheet">
   <!-- Bootstrap theme CSS -->
   <link href="bootstrap3_bookTheme/theme.css" rel="stylesheet">


   <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
   <!--[if lt IE 9]>
   <script src="bootstrap3_bookTheme/assets/js/html5shiv.js"></script>
   <script src="bootstrap3_bookTheme/assets/js/respond.min.js"></script>
   <![endif]-->
</head>

<body>

<?php include 'includes/book-header.inc.php'; ?>
   
<div class="container">
   <div class="row">  <!-- start main content row -->

      <div class="col-md-2">  <!-- start left navigation rail column -->
         <?php include 'includes/book-left-nav.inc.php'; ?>
      </div>  <!-- end left navigation rail --> 

      <div class="col-md-8">  <!-- start main content column -->
        
         <!-- book panel  -->
         <div class="panel panel-danger spaceabove"> 
         <?php if (!empty($search)) : ?>          
            <div class="panel-heading">
               <h4>My Customers [Last Name = <?php echo htmlspecialchars($search);?>]
               <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-sm btn-danger">
               <span class="glyphicon glyphicon-remove"></span> Remove Filter </a>               
            </h4>
            </div>
         <?php else : ?>
            <div class="panel-heading"><h4>My Customers </h4></div>
         <?php endif; ?>
           <table class="table">
             <tr>
             <th><a href="?sort=lastName"><span>Name</span></a> <?php if ($sort === 'lastName') echo '<span class="glyphicon glyphicon-arrow-down"></span>'; ?></th>
               <th>Email</th>
               <th>Address</th>
               <th><a href="?sort=city"><span>City</span></a> <?php if ($sort === 'city') echo '<span class="glyphicon glyphicon-arrow-down"></span>'; ?></th>
               <th><a href="?sort=country"><span>Country</span></a> <?php if ($sort === 'country') echo '<span class="glyphicon glyphicon-arrow-down"></span>'; ?></th>
             </tr>

            <?php foreach ($customers as $customer): ?>
               <tr>
                  <td><?php echo $customer['firstName'] . ' ' . $customer['lastName']; ?></td>
                  <td><?php echo $customer['email']; ?></td>
                  <td><?php echo $customer['address']; ?></td>
                  <td><?php echo $customer['city']; ?></td>
                  <td><?php echo $customer['country']; ?></td>
               </tr>
            <?php endforeach; ?>
            </table>
         </div>           
      </div>
      
      <!--<div class="col-md-2"> <-- start left navigation rail column --
         <div class="panel panel-info spaceabove">
            <div class="panel-heading"><h4>Categories</h4></div>
               <ul class="nav nav-pills nav-stacked">

               </ul> 
         </div>
         
         <div class="panel panel-info">
            <div class="panel-heading"><h4>Imprints</h4></div>
            <ul class="nav nav-pills nav-stacked">

            </ul>
         </div>         
      </div>  --> <!-- end left navigation rail --> 


      </div>  <!-- end main content column -->
   </div>  <!-- end main content row -->
</div>   <!-- end container -->
   


   
   
 <!-- Bootstrap core JavaScript
 ================================================== -->
 <!-- Placed at the end of the document so the pages load faster -->
 <script src="bootstrap3_bookTheme/assets/js/jquery.js"></script>
 <script src="bootstrap3_bookTheme/dist/js/bootstrap.min.js"></script>
 <script src="bootstrap3_bookTheme/assets/js/holder.js"></script>
</body>
</html>