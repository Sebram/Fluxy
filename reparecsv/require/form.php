<div class="row">
		<form action="require/traitment.php" method="post">
			<input type ="hidden" value="<?=$newfile?>" name='path'>
			<input type ="hidden" value="<?=$tablename?>" name='tablename'>
			<table class="table table-condensed">
				<?php foreach ( $Obj->getTable() as $k1 => $tableref ) { ?>
					<tr>

						<?php if ($tableref["COLUMN_NAME"] == $id_primaire) { ?>
							<td><input type ="text" value="<?=strtoupper($id_primaire)?>" name='tableref-<?=$k1?>' class="form-control" readonly> </td>
						<?php } 


						else { ?>
							<td><input type ="text" value="<?=strtoupper($tableref["COLUMN_NAME"])?>" name='tableref-<?=$k1?>' class="form-control" readonly> </td>
						<?php } ?>


						<!-- <td>

							<?php if ($k1 == 0) { ?>
							 <input type="text" name="typeslist-<?=$k1?>" class="form-control" value="varchar" readonly> 
							<?php } 


							else { ?>
								<input list="typeslist"  type="text" name="typeslist-<?=$k1?>" class="form-control"> 
								<datalist id="typeslist">
									<?php foreach ($types_values as $ktv => $type) { ?>
										<option value="<?=$type?>">
									<?php } ?>
								</datalist>
							<?php } ?>

						</td> -->
						
						<td>
							<?php if ($k1 == 0) { ?>
							 <input  type="text" name="csvref-<?=$k1?>" class="form-control" value=""  placeholder="tel, login, id ..."> 
							<?php } 
							else { ?>
								<input list="csvref"  type="text" name="csvref-<?=$k1?>" class="form-control" value=""> 
								<datalist id="csvref">
									<?php foreach (RepareCsv::getCsvRef($path)[0] as $kref => $ref) { ?>
													<option value="@<?=$ref?>">
									<?php } ?>
								</datalist>
							<?php } ?>
						</td>

					</tr>
				<?php } ?>
			</table>
			<input type="submit" class="btn btn-block btn-primary" >
		</form>
</div>
<br><br>