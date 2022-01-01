<?php 
	ob_start();
	session_start();

	$pageTitle = 'Categories';

	if (isset($_SESSION['Username'])) {
		
		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') {

		
			$sort = 'asc';

			$sort_array = array('asc', 'desc');

			if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {

				$sort = $_GET['sort'];

			}

			$stmt2 = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");

			$stmt2->execute();
			$cats = $stmt2->fetchAll(); ?>
			<h1 class="text-center">Manage Categories</h1>
			<div class="container categories">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-edit" ></i> Manage Categories
						<div class="option pull-right">
							  Ordering: <i class="fa fa-sort"></i> [
							<a class="<?php if ($sort == 'asc') { echo 'active'; } ?>" href="?sort=asc">Asc</a> | 
							<a class="<?php if ($sort == 'desc') { echo 'active'; } ?>" href="?sort=desc">Desc</a>]
							View: <i class="fa fa-eye"></i> [
							<span class="active" data-view='full'>Full</span> | 
							<span data-view='classic'>Classic</span> ]
						</div>
					</div>
					<div class="panel-body">
							<?php
								foreach($cats as $cat) {
									echo '<div class="cat">';
									echo "<div class='hidden-buttons'>";
											echo "<a href='categories.php?do=Edit&catid=" . $cat['ID'] . "' class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
												echo "<a href='categories.php?do=Delete&catid=" . $cat['ID'] . "' class='confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
											echo "</div>";
										echo '<h3>' . $cat['Name'] . '</h3>';
										echo '<div class="full-view">';
											echo "<p>"; if($cat['Description'] == '') { echo 'This category has no description'; } else { echo $cat['Description']; } echo "</p>";
											if($cat['Visibility'] == 1) { echo '<span class="visibility cat-span"><i class="fa fa-eye"></i> Hidden</span>'; } 
											if($cat['Allow_Comments'] == 1) { echo '<span class="commenting cat-span"><i class="fa fa-close"></i> Comment Disabled</span>'; }
											if($cat['Allow_Ads'] == 1) { echo '<span class="advertises cat-span"><i class="fa fa-close"></i> Ads Disabled</span>'; }
										echo '</div>'; 
									echo '</div>';
									echo '<hr>';
								}
							?>
						</div>
				</div>
				<a class="add-category btn btn-primary" href="categories.php?do=add"><i class="fa fa-plus"></i>Add New Categry</a>
			</div>
<?php
		} else if ($do == 'add') { ?>

			<h1 class="text-center">Add New Ctegory</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Insert" method="POST">
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10 col-sm-6">
								<input type="text" name="name" class="form-control" autocomplete="off" required="required" placeholder="Category To Add" onfocus="this.placeholder = ''" onblur="this.placeholder='Category To Add'"/>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10 col-sm-6">
								<input type="text" name="Description" class="form-control" placeholder = 'describe your Category' onfocus="this.placeholder = ''" onblur="this.placeholder='describe your Category'" />
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Ordering</label>
							<div class="col-sm-10 col-sm-6"> 
								<input type="text" name="ordering" class="form-control" placeholder="Number To Orderd Your Category" onfocus="this.placeholder = ''" onblur="this.placeholder='Number To Orderd Your Category'" />
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Visible</label>
							<div class="col-sm-10 col-sm-6">
								<div>
									<input id="vis-yes" type="radio" name="Visibilty" value="0" checked>
									<label for="vis-yes">Yes</label>
								</div>
								<div>
									<input id="vis-no" type="radio" name="Visibilty"  value="1">
									<label for="vis-no">No</label>
								</div>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Allow comments</label>
							<div class="col-sm-10 col-sm-6">
								<div>
									<input id="com-yes" type="radio" name="commenting" value="0" checked>
									<label for="com-yes">Yes</label>
								</div>
								<div>
									<input id="com-no" type="radio" name="commenting"  value="1">
									<label for="com-no">No</label>
								</div>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Allow Ads</label>
							<div class="col-sm-10 col-sm-6">
								<div>
									<input id="ads-yes" type="radio" name="ads" value="0" checked>
									<label for="ads-yes">Yes</label>
								</div>
								<div>
									<input id="ads-no" type="radio" name="ads"  value="1">
									<label for="ads-no">No</label>
								</div>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10 col-sm-6">
							<input type="submit" value="Add New Category" class="btn btn-primary btn-lg " >
							</div>
						</div>
					</form>
				</div>
				<?php
		} else if ($do == 'Insert') {

					if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					echo "<h1 class='text-center'>Insert Category</h1>";
					echo "<div class='container'>";
					
					$name 				=	$_POST['name'];
					$Description 		=	$_POST['Description'];
					$ordering 			=	$_POST['ordering'];
					$Visible 			=	$_POST['Visibilty'];
					$commenting			=	$_POST['commenting'];
					$ads 				=	$_POST['ads'];


						$check = checkItem("Name","categories",$name);
						if($check==1) {
							$theMsg = '<div class="alert alert-danger">Sorry User Is Already Exist</div>';
							redirectHome($theMsg, 'back');
						}	else {


								$stmt = $con->prepare("INSERT INTO 
															categories(Name, Description, Ordering, Visibility,Allow_Comments, Allow_Ads)
														VALUES(:zname, :zDescription, :zordering, :zVisibility, :zcommenting, :zads)");
								$stmt->execute(array(

									'zname' 		=> $name,
									'zDescription' 	=> $Description,
									'zordering' 	=> $ordering,
									'zVisibility' 	=> $Visible,
									'zcommenting' 	=> $commenting,
									'zads' 			=> $ads
								));

								$theMsg =  "<div class='alert alert-success'>" . $stmt->rowCount() . ' successful Record</div>';
									redirectHome($theMsg,'back');
							}
											
					} else {
				echo "<div class='container'>";
				 	$theMsg = "<div class='alert alert-danger'> sorry you cant do that</div>";
				 	redirectHome($theMsg);
				 	echo "</div>";
				}
				echo "</div>";

		} else if ($do == 'Edit') {

			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

			
			$stmt = $con->prepare("SELECT * FROM categories WHERE ID = ?");
			$stmt->execute(array($catid));
			$cat = $stmt->fetch();
			$count = $stmt->rowCount();
			if ($count > 0) { ?>
				
				<h1 class="text-center"> Edit Ctegory</h1>
				<div class="container">
					<form class="form-horizontal" action="?do=Update" method="POST">
						<input type="hidden" name="catid" value="<?php echo $catid; ?>">
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Name</label>
							<div class="col-sm-10 col-sm-6">
								<input type="text" name="name" class="form-control" required="required" placeholder="Category To Add" onfocus="this.placeholder = ''" onblur="this.placeholder='Category To Add'" value="<?php echo $cat['Name']; ?> " />
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Description</label>
							<div class="col-sm-10 col-sm-6">
								<input type="text" name="Description" class="form-control" value="<?php echo $cat['Description']; ?> " />
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Ordering</label>
							<div class="col-sm-10 col-sm-6"> 
								<input type="text" name="Ordering" class="form-control" placeholder="Number To Orderd Your Category" onfocus="this.placeholder = ''" onblur="this.placeholder='Number To Orderd Your Category'" value="<?php echo $cat['Ordering']; ?> " />
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Visible</label>
							<div class="col-sm-10 col-sm-6">
								<div>
									<input id="vis-yes" type="radio" name="Visibilty" value="0" <?php if ($cat['Visibility']==0) {
										echo 'checked';
									} ?> >
									<label for="vis-yes">Yes</label>
								</div>
								<div>
									<input id="vis-no" type="radio" name="Visibilty"  value="1" <?php if ($cat['Visibility']==1) {
										echo 'checked';
									} ?> >
									<label for="vis-no">No</label>
								</div>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Allow comments</label>
							<div class="col-sm-10 col-sm-6">
								<div>
									<input id="com-yes" type="radio" name="commenting" value="0" <?php  if ($cat['Allow_Comments']==0) {
										echo 'checked';
									} ?> >
									<label for="com-yes">Yes</label>
								</div>
								<div>
									<input id="com-no" type="radio" name="commenting"  value="1" <?php  if ($cat['Allow_Comments']==1) {
										echo 'checked';
									}?> >
									<label for="com-no">No</label>
								</div>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<label class="col-sm-2 control-label">Allow Ads</label>
							<div class="col-sm-10 col-sm-6">
								<div>
									<input id="ads-yes" type="radio" name="ads" value="0" <?php  if ($cat['Allow_Ads']==0) {
										echo 'checked';
									} ?> >
									<label for="ads-yes">Yes</label>
								</div>
								<div>
									<input id="ads-no" type="radio" name="ads"  value="1" <?php if ($cat['Allow_Ads']==1) {
										echo 'checked';
									} ?> >
									<label for="ads-no">No</label>
								</div>
							</div>
						</div>
						<div class="form-group form-group-lg">
							<div class="col-sm-offset-2 col-sm-10 col-sm-6">
								<input type="submit" value="Update Ctegory" class="btn btn-primary btn-lg" />
							</div>
						</div>
					</form>
				</div>
		<?php
	} else {

				echo "<div class='container'>";
					$theMsg = "<div class='alert alert-danger'> No Such ID</div>";
					redirectHome($theMsg);
					echo "</div>";
				}
				echo "</div>";

		} else if ($do == 'Update') {
			if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					echo "<h1 class='text-center'> Update Category</h1>";
				echo "<div class='container'>";
				
					
					$id 		=	$_POST['catid'];
					$name 		= $_POST['name'];
					$desc 		= $_POST['Description'];
					$order 		= $_POST['Ordering'];
					$visible 	= $_POST['Visibilty'];
					$comment 	= $_POST['commenting'];
					$ads 		= $_POST['ads'];

					$stmt = $con->prepare("UPDATE 
												categories 
											SET 
												Name = ?, 
												Description = ?, 
												Ordering = ?, 
												Visibility = ?,
												Allow_Comments = ?,
												Allow_Ads = ?									
											WHERE
												ID = ?");

					$stmt->execute(array($name, $desc, $order, $visible, $comment, $ads, $id));

					$theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Success Edit</div>';
					redirectHome($theMsg,'back');

				
					
				} else {
					$theMsg = "<div class='alert alert-danger'>sorry you cant do that</div>";
					redirectHome($theMsg);
				}
				echo "</div>";

		} else if ($do == 'Delete') {
			echo "<h1 class='text-center'> Delete Member</h1>";
		echo "<div class='container'>";
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

				$check = checkItem('ID', 'categories', $catid);


				if ($check > 0) {
					$stmt = $con->prepare("DELETE FROM categories WHERE ID = :zid");
					$stmt-> bindparam(":zid", $catid);
					$stmt->execute();
					$theMsg =  "<div class='alert alert-success'> " . $stmt->rowCount() . ' User Deleted successfuly</div>';
					redirectHome($theMsg, 'back');
				} else{
					$theMsg = "<div class='alert alert-danger'>Some Thing Went Wrong</div>";
					redirectHome($theMsg, 'back');
				}
			echo '</div';

	}

		include  $tp1 . 'footer.php';

	} else {
		header('Location: index.php');
		exit();
	}

	ob_end_flush();

?>