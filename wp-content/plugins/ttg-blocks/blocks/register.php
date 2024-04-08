<?php
$blocks = new TTG_Blocks();

$blocks->set_base_scripts(
	[
		[
			'handle' => 'ttg-blocks-base',
			'url' => TTG_Blocks_Utils::get_assets_url('dist/css/base.min.css')
		]
	]
);

$blocks->add(
	'styling-box',
	'TTG Styling Box',
	'TTG Styling Box',
	[
		[
			'handle' => 'ttg-media',
			'url' => TTG_Blocks_Utils::get_assets_url('dist/css/components/media.css')
		],
		[
			'handle' => 'background',
			'url' => TTG_Blocks_Utils::get_assets_url('dist/css/components/ttg-background.css')
		],
		[
			'handle' => 'styling-box',
			'url' => TTG_Blocks_Utils::get_assets_url('dist/css/components/styling-box.css')
		]
	],
	[
		[
			'handle' => 'ttg-media',
			'url' => TTG_Blocks_Utils::get_assets_url('dist/js/components/media.js')
		]
	]
);

$blocks->add(
	'separator',
	'TTG Separator',
	'TTG Separator',
	[
		[
			'handle' => 'separator',
			'url' => TTG_Blocks_Utils::get_assets_url('dist/css/components/separator.css')
		],
	]
);

$blocks->add(
	'buttons',
	'TTG Buttons',
	'TTG Buttons',
	[
		[
			'handle' => 'button',
			'url' => TTG_Blocks_Utils::get_assets_url('dist/css/components/button.css')
		]
	]
);

$blocks->add(
	'media',
	'TTG Media',
	'TTG Media',
	[
		[
			'handle' => 'ttg-media',
			'url' => TTG_Blocks_Utils::get_assets_url('dist/css/components/media.css')
		]
	],
	[
		[
			'handle' => 'ttg-media',
			'url' => TTG_Blocks_Utils::get_assets_url('dist/js/components/media.js')
		]
	]
);

$blocks->add(
	'image',
	'TTG Image',
	'TTG Image',
	[
		[
			'handle' => 'ttg-image',
			'url' => TTG_Blocks_Utils::get_assets_url('dist/css/components/image.css')
		]
	],
	[
		[
			'handle' => 'ttg-image',
			'url' => TTG_Blocks_Utils::get_assets_url('dist/js/components/bg.js')
		]
	],
);

$blocks->add(
	'show-more-text',
	'TTG Show More Text',
	'TTG Show More Text',
	[
		[
			'handle' => 'ttg-show-more-text',
			'url' => TTG_Blocks_Utils::get_assets_url('dist/css/components/ttg-show-more-text.css')
		]
	],
	[
		[
			'handle' => 'ttg-show-more-text',
			'url' => TTG_Blocks_Utils::get_assets_url('dist/js/components/ttg-show-more-text.js')
		]
	],
);
