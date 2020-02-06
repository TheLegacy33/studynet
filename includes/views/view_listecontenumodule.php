<?php
	/**
	 * @var Module $module
	 * @var ContenuModule $contenuModule
	 * @var Periodeformation $pf
	 * @var $listeContenuModule
	 */
?>
<nav class="navinterne">
	<a href="<?php print(ROOTHTML); ?>/index.php?p=periodesformation&a=listemodules&idpf=<?php print($pf->getId()); ?>" title="Retour à la liste des modules"><< Retour</a>
</nav>
<section id="content_body" class="container">
	<header class="col-12 text-center text-info header-section">
		Contenu du module <?php print($module->getLibelle().(!is_null($module->getCode())?'<i style="font-size:0.6em"> ('.$module->getCode().')</i>':'')); ?><br />
		<i>Dispensé par : <?php print($module->getIntervenant()); ?></i>
	</header>
	<?php
		if (isset($user)){
			if ($user->isAdmin() OR $pf->getResponsable() == $user){
				?>
				<div class="col-12 btnactions">
					<a href="<?php print(ROOTHTML); ?>/index.php?p=periodesformation&a=ajoutcontenumodule&idpf=<?php print($pf->getId()); ?>&idmodule=<?php print($module->getId()); ?>" class="btn btn-secondary" title="Ajout d'un contenu de module">Nouveau contenu<span class="fa fa-plus"></span></a>
				</div>
				<?php
			}
		}
	?>
	<div class="col-12">
		<table class="table table-bordered table-hover table-responsive-md">
			<thead class="thead-light">
			<tr>
				<th style="width: 600px;"></th>
				<th style="width: 60px;" colspan="3">Actions</th>
			</tr>
			</thead>
			<tbody>
			<?php
				$script = '';

				if (!$module->hasContenu()){
					$script .= '<tr><td colspan="8">Aucune donnée disponible !</td></tr>';
				}else{
					foreach ($module->getContenu() as $contenuModule){
						$script .= '<tr class="lignetab">';
						$script .= '<td style="border: none; text-align: left">'.$contenuModule->getLibelle().'</td>';
						$script .= '<td><a href="index.php?p=periodesformation&a=editcontenumodule&idpf='.$pf->getId().'&idmodule='.$module->getId().'&idcmodule='.$contenuModule->getId().'" title="Editer le contenu"><span class="fa fa-edit"></span></a></td>';
						$script .= '<td><a style="cursor: pointer" data-name="dropmodule" data-id="'.$contenuModule->getId().'" title="Supprimer le contenu"><span class="fa fa-trash"></span></a></td>';
						$script .= '</tr>';
					}
				}
				print($script);
			?>
			</tbody>
		</table>
	</div>
</section>
