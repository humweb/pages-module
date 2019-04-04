<?php

namespace Humweb\Pages\Settings;

use Humweb\Menus\Models\Menu;
use Humweb\Settings\SettingsSchema;

//@TODO Creat setting for default page layout
class SiteSettingsSchema extends SettingsSchema
{
    public function __construct($values = [], $decorator = null)
    {
        parent::__construct($values, $decorator);

        $this->settings = [
            'site.menu' => [
                'type'        => 'select',
                'label'       => 'Top Menu',
                'description' => 'What to show in main site menu.',
                'options'     => function () {
                    return ['0' => 'Pages'] + Menu::pluck('title', 'id');
                }
            ]
        ];
    }
}
