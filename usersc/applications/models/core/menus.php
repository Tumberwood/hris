<?php
	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );
	
	use
		DataTables\Editor,
		DataTables\Editor\Field,
		DataTables\Editor\Format,
		DataTables\Editor\Mjoin,
		DataTables\Editor\Options,
		DataTables\Editor\Upload,
		DataTables\Editor\Validate,
		DataTables\Editor\ValidateOptions,
		DataTables\Editor\Query,
		DataTables\Editor\Result;
	
	$editor = Editor::inst( $db, 'menus' )
		->debug(true)
		->fields(
			Field::inst( 'menus.id' ),
			Field::inst( 'menus.menu_title' )
				->set( Field::SET_CREATE )
				->setValue('side'),
			Field::inst( 'menus.parent' )
				->setFormatter( Format::ifEmpty( 0 ) )
				->options( Options::inst()
					->table( 'menus' )
					->value( 'id' )
					->label( ['display_order','label'] )
					->where( function ($q) {
						$q
							->where( 'dropdown', 1 )
							->where( 'menu_title', 'side' );
					})
					->render( function ( $row ) {
						return $row['display_order'] . ' - ' .$row['label'];
					} )
					->order( 'display_order,label' )
				),
			Field::inst( 'menus.dropdown' ),
			Field::inst( 'menus.logged_in' ),
			Field::inst( 'menus.display_order' ),
			Field::inst( 'menus.label' ),
			Field::inst( 'menus.link' ),
			Field::inst( 'menus.icon_class' ),

			Field::inst( 'menus_parent.label' )
		)
        ->leftJoin( 'menus as menus_parent','menus_parent.id','=','menus.parent' )
		->join(
			Mjoin::inst( 'permissions' )
				->link( 'menus.id', 'groups_menus.menu_id' )
				->link( 'permissions.id', 'groups_menus.group_id' )
				->fields(
					Field::inst( 'id' )
						->setFormatter( Format::ifEmpty( 0 ) )
						->options( Options::inst()
							->table( 'permissions' )
							->value( 'id' )
							->label( ['name'] )
							->render( function ( $row ) {
								return $row['name'];
							} )
							->order( 'name' )
						),
					Field::inst( 'name' )
				)
		)
		->where( 'menus.menu_title', 'side');

	$editor
		->on( 'preCreate', function ( $editor, &$values ) {
			$parent = $values['menus']['parent'];
			$dropdown = $values['menus']['dropdown'];
			if($parent == ''){
				$editor
					->field( 'menus.parent' )
					->setValue( -1 );
			}
			
		} )
		->on( 'preEdit', function ( $editor, $id, &$values ) {
			$parent = $values['menus']['parent'];
			$dropdown = $values['menus']['dropdown'];
			if($parent == ''){
				$editor
					->field( 'menus.parent' )
					->setValue( -1 );
			}
		} );
	
	// input log
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>