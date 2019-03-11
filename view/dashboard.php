<div class="fd-migration-wrap">
    <h1>FD Migration Option</h1>

	<?php if ( isset( $terms ) ): ?>
        <table border="1">
            <tr>
                <td>id</td>
                <td>Term Name</td>
                <td>Action</td>
            </tr>
			<?php foreach ( $terms as $term ): ?>
                <tr>
                    <td><?php echo $term->term_id; ?></td>
                    <td><?php echo $term->name; ?></td>
                    <td><button class="fd-run-migrate" data-term="<?php echo $term->term_id; ?>">Migrate Data</button></td>
                </tr>
			<?php endforeach; ?>
        </table>
	<?php endif; ?>
</div>