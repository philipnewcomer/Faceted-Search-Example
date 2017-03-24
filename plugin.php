<?php
/**
 * Plugin Name: Faceted Search Example
 * Plugin URI:  https://github.com/philipnewcomer/Faceted-Search-Example
 * Description: Demonstrates a basic faceted search interface powered by ElasticPress.
 * Version:     0.1.0
 * Author:      Philip Newcomer
 * Author URI:  https://philipnewcomer.net
 * License:     GPLv2 or later
 *
 * Copyright (C) 2017 Philip Newcomer
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

namespace PhilipNewcomer\Faceted_Search_Example;

require_once( __DIR__ . '/src/aggregations.php' );
require_once( __DIR__ . '/src/helpers.php' );
require_once( __DIR__ . '/src/meta-box.php' );
require_once( __DIR__ . '/src/models.php' );
require_once( __DIR__ . '/src/query.php' );
require_once( __DIR__ . '/src/widget.php' );
