# SIPORA Social Feed Media - Batch 8A

15 JPEG: 5 Community update, 5 Activity update, 3 Youth post, 2 Opportunity update. Plus 5 text-only posts in manifest and caption CSV. 11 images reuse existing scenes (cropped for feed); 4 new scenes use built-in imagegen. Youth photos reference the fictional profile portraits for Erik, Aulia, and Dimas.

Dimensions: 12 square 1000x1000 and 3 portrait 1000x1250. Crops are feed derivatives; source paths and source hashes are retained in manifest.json. Full Activity documentation is preserved. No visible real logos or readable generated text. The certificate prop has only abstract placeholder lines.

manifest.json: media composition, captions, source files/hashes, owners and editorial related IDs. prompts.json: the four generation briefs and profile references. post-copy.csv: all 20 captions. app-post-mapping.json: verified local post UUIDs and ownership. index.html: portable asset gallery.

Native SocialPost has owner/body/image, not an editorial category field. The five/five/three/two split labels these asset captions; Activity and Opportunity discovery events remain their separate native feed kinds. Posts appear in the all feed and appropriate owner views.

Seeder: php artisan db:seed --class=DemoSocialFeedMediaSeeder. Only local environment and database sipora are allowed. 15 images are served privately, stored by content hash, and five posts have no image. Reseeding does not duplicate posts. Existing development accounts are preserved; three dedicated asset identities are added for Aulia, Dimas and Nabila where older fixture names differ. All people and posts are fictional. No external publishing or notifications.
