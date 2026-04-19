<?php
session_start();
include("config.php");

if(!isset($_SESSION["role"]) || $_SESSION["role"] != "admin"){
    header("Location: ../login.php");
    exit();
}

// CREATE PRODUCT
if(isset($_POST['add_product'])){
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);

    $image = "";
    if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
        $image = time() . "_" . basename($_FILES['image']['name']);
        $target = "uploads/" . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $insert = "INSERT INTO products(name, category, price, stock, image)
               VALUES('$name', '$category', '$price', '$stock', '$image')";
    mysqli_query($conn, $insert);

    header("Location: products.php");
    exit();
}

// DELETE PRODUCT
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);

    $imgQuery = mysqli_query($conn, "SELECT image FROM products WHERE id='$id'");
    $imgData = mysqli_fetch_assoc($imgQuery);

    if($imgData && !empty($imgData['image']) && file_exists("uploads/" . $imgData['image'])){
        unlink("uploads/" . $imgData['image']);
    }

    mysqli_query($conn, "DELETE FROM products WHERE id='$id'");

    header("Location: products.php");
    exit();
}

// GET PRODUCT FOR EDIT
$editData = null;
if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $editQuery = mysqli_query($conn, "SELECT * FROM products WHERE id='$id'");
    $editData = mysqli_fetch_assoc($editQuery);
}

// UPDATE PRODUCT
if(isset($_POST['update_product'])){
    $id = intval($_POST['product_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $stock = mysqli_real_escape_string($conn, $_POST['stock']);
    $old_image = mysqli_real_escape_string($conn, $_POST['old_image']);

    $new_image = $old_image;

    if(isset($_FILES['image']) && $_FILES['image']['name'] != ""){
        $new_image = time() . "_" . basename($_FILES['image']['name']);
        $target = "uploads/" . $new_image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);

        if(!empty($old_image) && file_exists("uploads/" . $old_image)){
            unlink("uploads/" . $old_image);
        }
    }

    $update = "UPDATE products 
               SET name='$name', category='$category', price='$price', stock='$stock', image='$new_image'
               WHERE id='$id'";
    mysqli_query($conn, $update);

    header("Location: products.php");
    exit();
}

// READ PRODUCTS
$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Products - Hapana Fireworks</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
body{
    margin:0;
    background:#000;
    color:white;
    font-family:'Segoe UI',sans-serif;
}
.sidebar{
    min-height:100vh;
    background:#111;
    padding-top:30px;
}
.sidebar h4{
    color:#ffc107;
    margin-bottom:25px;
}
.sidebar a{
    color:white;
    display:block;
    padding:12px 20px;
    text-decoration:none;
    transition:0.3s;
    margin:4px 10px;
    border-radius:8px;
}
.sidebar a:hover,
.sidebar a.active{
    background:linear-gradient(45deg,#ff9800,#ffc107);
    color:black;
}
.main-content{
    padding:30px;
}
.form-box, .table-box{
    background:#111;
    border:1px solid #333;
    border-radius:15px;
    padding:25px;
    box-shadow:0 0 20px rgba(255,193,7,0.08);
}
.form-control, .form-select{
    background:#000;
    color:white;
    border:1px solid #444;
}
.form-control:focus, .form-select:focus{
    background:#000;
    color:white;
    border-color:#ffc107;
    box-shadow:none;
}
.form-control::placeholder{
    color:#aaa;
}
.table{
    color:white;
    vertical-align:middle;
}
.table th, .table td{
    border-color:#333 !important;
}
.table img{
    width:70px;
    height:70px;
    object-fit:cover;
    border-radius:10px;
    border:1px solid #444;
}
.btn-warning{
    font-weight:600;
}
.btn-sm{
    border-radius:8px;
}
h2, h4{
    color:#ffc107;
}
.preview-img{
    width:90px;
    height:90px;
    object-fit:cover;
    border-radius:10px;
    border:1px solid #444;
    margin-top:10px;
}
</style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 sidebar">
            <h4 class="text-center">Admin Panel</h4>
            <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="suppliers.php"><i class="bi bi-people"></i> Suppliers</a>
            <a href="products.php" class="active"><i class="bi bi-box"></i> Products</a>
            <a href="orders.php"><i class="bi bi-cart"></i> Orders</a>
            <a href="users.php"><i class="bi bi-person"></i> Users</a>
            <a href="dilivery.php"><i class="bi bi-truck"></i> Dilivery</a>
            <a href="../logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>

        <div class="col-md-10 main-content">
            <h2 class="mb-4">Product Management</h2>

            <div class="form-box mb-4">
                <h4 class="mb-4"><?php echo $editData ? "Edit Product" : "Add Product"; ?></h4>

                <form method="POST" enctype="multipart/form-data" onsubmit="return validateProduct()">
                    <?php if($editData){ ?>
                        <input type="hidden" name="product_id" value="<?php echo $editData['id']; ?>">
                        <input type="hidden" name="old_image" value="<?php echo $editData['image']; ?>">
                    <?php } ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="mb-2">Product Name</label>
                            <input type="text" name="name" class="form-control" required onkeypress="return onlyLetters(event)"
                                   value="<?php echo $editData ? htmlspecialchars($editData['name']) : ''; ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="mb-2">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="">Select Category</option>
                                <option value="Rockets" <?php if($editData && $editData['category']=="Rockets") echo "selected"; ?>>Rockets</option>
                                <option value="Crackers" <?php if($editData && $editData['category']=="Crackers") echo "selected"; ?>>Crackers</option>
                                <option value="Fountains" <?php if($editData && $editData['category']=="Fountains") echo "selected"; ?>>Fountains</option>
                                <option value="Sparklers" <?php if($editData && $editData['category']=="Sparklers") echo "selected"; ?>>Sparklers</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="mb-2">Price</label>
                            <input type="number" step="0.01" name="price" class="form-control" required
                                  min="0"
                                  oninput="if(this.value < 0) this.value = 0;"
                                   value="<?php echo $editData ? $editData['price'] : ''; ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="mb-2">Stock</label>
                            <input type="number" name="stock" class="form-control" required
                                  min="0"
                                  oninput="if(this.value < 0) this.value = 0;" 
                                   value="<?php echo $editData ? $editData['stock'] : ''; ?>">
                        </div>

                        <div class="col-md-4">
                            <label class="mb-2">Image</label>
                            <input type="file" name="image" class="form-control" <?php echo $editData ? '' : 'required'; ?>>
                        </div>
                    </div>

                    <?php if($editData && !empty($editData['image'])){ ?>
                        <div class="mt-3">
                            <label class="mb-2">Current Image</label><br>
                            <img src="uploads/<?php echo $editData['image']; ?>" class="preview-img" alt="Product Image">
                        </div>
                    <?php } ?>

                    <div class="mt-4">
                        <?php if($editData){ ?>
                            <button type="submit" name="update_product" class="btn btn-warning">Update Product</button>
                            <a href="products.php" class="btn btn-secondary">Cancel</a>
                        <?php } else { ?>
                            <button type="submit" name="add_product" class="btn btn-warning">Add Product</button>
                        <?php } ?>
                    </div>
                </form>
            </div>


            <a href="products_report.php" class="btn btn-warning mb-3">
    <i class="bi bi-file-earmark-pdf"></i> Download Products Report
</a>

            <div class="table-box">
                <h4 class="mb-4">All Products</h4>

                <div class="table-responsive">
                    <table class="table table-dark table-bordered text-center align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th width="240">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($products) > 0){ ?>
                                <?php while($row = mysqli_fetch_assoc($products)){ ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td>
                                            <?php if(!empty($row['image'])){ ?>
                                                <img src="uploads/<?php echo $row['image']; ?>" alt="Product">
                                            <?php } else { ?>
                                                No Image
                                            <?php } ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                                        <td>LKR <?php echo number_format($row['price'], 2); ?></td>
                                        <td><?php echo $row['stock']; ?></td>
                                        <td>
                                            <a href="products.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <a href="products.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i class="bi bi-trash"></i> Delete
                                            </a>
                                            <a href="../product_view.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="7">No products found</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
function validateProduct() {
    let name = document.querySelector('input[name="name"]').value.trim();
    let price = document.querySelector('input[name="price"]').value;
    let stock = document.querySelector('input[name="stock"]').value;

    if(name === ""){
        alert("Product name required");
        return false;
    }

    if(price === "" || isNaN(price) || price <= 0){
        alert("Enter valid price");
        return false;
    }

    if(stock === "" || isNaN(stock) || stock < 0){
        alert("Enter valid stock");
        return false;
    }

    return true;
}
</script>
<script>
function onlyLetters(e) {
    let char = String.fromCharCode(e.which);

    if (!/^[a-zA-Z\s]+$/.test(char)) {
        return false;
    }

    return true;
}
</script>
</body>
</html>