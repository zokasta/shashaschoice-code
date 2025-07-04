<?php
/**
 * @package WordPress
 * @subpackage Theme_Compat
 * @deprecated 3.0.0
 *
 * This file is here for backward compatibility with old themes and will be removed in a future version
 */
_deprecated_file(
	/* translators: %s: Template name. */
	sprintf( __( 'Theme without %s' ), basename( __FILE__ ) ),
	'3.0.0',
	null,
	/* translators: %s: Template name. */
	sprintf( __( 'Please include a %s template in your theme.' ), basename( __FILE__ ) )
);
?>

<hr />
<div id="footer" role="contentinfo">
<!-- If you'd like to support WordPress, having the "powered by" link somewhere on your blog is the best way; it's our only promotion or advertising. -->
	<p>
		<?php
		printf(
			/* translators: 1: Site name, 2: WordPress */
			__( '%1$s is proudly powered by %2$s' ),
			get_bloginfo( 'name' ),
			'<a href="https://wordpress.org/">WordPress</a>'
		);
		?>
	</p>
</div>
</div>

<!-- Gorgeous design by Michael Heilemann - http://binarybonsai.com/ -->
<?php /* "Just what do you think you're doing Dave?" */ ?>

		<?php wp_footer(); ?>
</body>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Get all <a> elements on the page
    const anchorTags = document.querySelectorAll("a");

    anchorTags.forEach(anchor => {
      // Check if the <a> tag has no href attribute
      if (!anchor.hasAttribute("href") || anchor.getAttribute("href").trim() === "") {
        // Create a <p> element
        const p = document.createElement("p");
        p.innerHTML = anchor.innerHTML;

        // Replace <a> with <p>
        anchor.parentNode.replaceChild(p, anchor);
      }
    });
  });
</script>

</html>
