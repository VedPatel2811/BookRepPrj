<?php
require "includes/database.inc.php";

$selectedCategoryID = $imprintID = $statusID = $bindingID = null;

if (isset($_GET['cat'])) {
   $selectedCategoryID = $_GET['cat'];
} elseif (isset($_GET['imp'])) {
   $imprintID = $_GET['imp'];
} elseif (isset($_GET['sta'])) {
   $statusID = $_GET['sta'];
} elseif (isset($_GET['bin'])) {
   $bindingID = $_GET['bin'];
}

$whereConditions = [];
$params = [];

if ($selectedCategoryID !== null) {
   $whereConditions[] = "SubcategoryID IN (SELECT ID FROM Subcategories WHERE CategoryID = ?)";
   $params[] = $selectedCategoryID;
}
if ($imprintID !== null) {
   $whereConditions[] = "ImprintID = ?";
   $params[] = $imprintID;
}
if ($statusID !== null) {
   $whereConditions[] = "ProductionStatusID = ?";
   $params[] = $statusID;
}
if ($bindingID !== null) {
   $whereConditions[] = "BindingTypeID = ?";
   $params[] = $bindingID;
}

$sql = "SELECT * FROM Books";

if (!empty($whereConditions)) {
   $sql .= " WHERE " . implode(" AND ", $whereConditions);
}

$sql .= " ORDER BY Title";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

$subCategoriesQuery = "SELECT * FROM Subcategories ORDER BY SubcategoryName";
$subCategories = $pdo->prepare($subCategoriesQuery);
$subCategories->execute();
$subCategoriesDetails = $subCategories->fetchAll(PDO::FETCH_ASSOC);

$imprintQuery = "SELECT * FROM Imprints ORDER BY Imprint";
$imprints = $pdo->prepare($imprintQuery);
$imprints->execute();
$imprintsDetails = $imprints->fetchAll(PDO::FETCH_ASSOC);

$statusQuery = "SELECT * FROM ProductionStatuses ORDER BY ProductionStatus";
$productionStatus = $pdo->prepare($statusQuery);
$productionStatus->execute();
$productionStatusDetails = $productionStatus->fetchAll(PDO::FETCH_ASSOC);

$bindingQuery = "SELECT * FROM BindingTypes ORDER BY BindingType";
$bindingType = $pdo->prepare($bindingQuery);
$bindingType->execute();
$bindingTypeDetails = $bindingType->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="en">
<head>
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

      <div class="col-md-6">  <!-- start main content column -->
        
         <!-- book panel  -->
         <div class="panel panel-danger spaceabove">    
         <?php if ($selectedCategoryID !== null || $imprintID !== null || $statusID !== null || $bindingID !== null) : ?>       
           <div class="panel-heading"> 
               <?php if ($selectedCategoryID !== null) : ?>
                  <h4>Catalog [Categories = <?php echo $selectedCategoryID; ?>]
               <?php elseif ($imprintID !== null) : ?>
                  <h4>Catalog [Imprint = <?php echo $imprintID; ?>]
               <?php elseif ($statusID !== null) : ?>
                  <h4>Catalog [Status = <?php echo $statusID; ?>]
               <?php elseif ($bindingID !== null) : ?>
                  <h4>Catalog [Binding = <?php echo $bindingID; ?>]
               <?php endif; ?>
                  <a href="book-list.php" class="btn btn-sm btn-danger">
                     <span class="glyphicon glyphicon-remove"></span> Remove Filter 
                  </a>
               </h4>
            </div>
            <?php else : ?>
               <div class="panel-heading"><h4>Catalog</h4></div>
            <?php endif; ?>
            
           <table class="table">
             <tr>
               <th>Cover</th>
               <th>ISBN</th>
               <th>Title</th>
             </tr>

             <?php foreach ($books as $book): ?>
               <tr>
                  <td><?php $imagePath = 'images/tinysquare/' . $book['ISBN10'] . '.jpg'; ?>
                     <?php if (file_exists($imagePath)) : ?>
                        <img src="<?php echo $imagePath; ?>" alt="<?php echo $book['Title']; ?>">
                     <?php endif; ?>
                  </td>
                  <td><?php echo $book['ISBN10'];?></td>
                  <td><a href="book-details.php?id=<?php echo $book['ID']; ?>">
                        <?php echo $book['Title']; ?>
                     </a>
                  </td>
               </tr>
            <?php endforeach; ?>
           </table>
         </div>           
      </div>
      
      <div class="col-md-2">  <!-- start left navigation rail column -->
         <div class="panel panel-info spaceabove">
            <div class="panel-heading"><h4>Categories</h4></div>
               <ul class="nav nav-pills nav-stacked">
                  <br>
               <?php for ($i = 0; $i < 20; $i++) : ?>
                  <a href="book-list.php?cat=<?php echo $subCategoriesDetails[$i]['CategoryID']; ?>">
                  <li>&nbsp;&nbsp;&nbsp;<?php echo $subCategoriesDetails[$i]['SubcategoryName']; ?></li><br>
               </a>
               <?php endfor; ?>
             </ul> 
         </div>
                 
      </div>  <!-- end left navigation rail --> 
      
      <div class="col-md-2">  <!-- start left navigation rail column -->
         <div class="panel panel-info spaceabove">
            <div class="panel-heading"><h4>Imprints</h4></div>
            <ul class="nav nav-pills nav-stacked">
            <br>
               <?php foreach ($imprintsDetails as $imprintDetail): ?>
                  <a href="book-list.php?imp=<?php echo $imprintDetail['ID']; ?>">
                  <li>&nbsp;&nbsp;&nbsp;<?php echo $imprintDetail['Imprint']; ?></li><br>
               </a>
               <?php endforeach; ?>
             </ul>
         </div>    

         <div class="panel panel-info">
            <div class="panel-heading"><h4>Status</h4></div>
            <ul class="nav nav-pills nav-stacked">
            <br>
               <?php foreach ($productionStatusDetails as $ProductionStatusDetail): ?>
                  <a href="book-list.php?sta=<?php echo $ProductionStatusDetail['ID']; ?>">
                  <li>&nbsp;&nbsp;&nbsp;<?php echo $ProductionStatusDetail['ProductionStatus']; ?></li><br>
               </a>
               <?php endforeach; ?>
             </ul>
         </div>  
         
         <div class="panel panel-info">
            <div class="panel-heading"><h4>Binding</h4></div>
            <ul class="nav nav-pills nav-stacked">
            <br>
               <?php foreach ($bindingTypeDetails as $BindingTypeDetail): ?>
                  <a href="book-list.php?bin=<?php echo $BindingTypeDetail['ID']; ?>">
                  <li>&nbsp;&nbsp;&nbsp;<?php echo $BindingTypeDetail['BindingType']; ?></li><br>
               </a>
               <?php endforeach; ?>
             </ul>
         </div>           
      </div>  <!-- end left navigation rail -->       


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