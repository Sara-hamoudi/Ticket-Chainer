const $ = require('jquery');
require('popper.js');
require('../robust-admin/vendors/js/ui/jquery.matchHeight-min');
require('../robust-admin/vendors/js/ui/jquery-sliding-menu');
require('../robust-admin/js/core/libraries/jquery_ui/full.min');
require('bootstrap');

require('../robust-admin/vendors/js/extensions/underscore-min');
require('../robust-admin/vendors/js/ui/unison.min');
require('../robust-admin/vendors/js/ui/screenfull.min');
require('../robust-admin/vendors/js/ui/perfect-scrollbar.jquery.min');

require('select2');
require('daterangepicker');

// dataTables dependencies

require('datatables.net');
require('datatables.net-autofill');
require('datatables.net-bs4');
require('datatables.net-buttons');
require('datatables.net-buttons-bs4');
require('datatables.net-responsive');
require('datatables.net-fixedheader');

window.JSZip = require('../robust-admin/vendors/js/tables/jszip.min');
const pdfMake = require('../robust-admin/vendors/js/tables/pdfmake.min');
const pdfFonts = require('../robust-admin/vendors/js/tables/vfs_fonts');
pdfMake.vfs = pdfFonts.pdfMake.vfs;
require('../robust-admin/vendors/js/tables/buttons.html5.min');
require('../robust-admin/vendors/js/tables/buttons.print.min');
require('../robust-admin/vendors/js/tables/buttons.colVis.min');

require('./scripts/datatable-defaults');

require('./scripts/moment-defaults');
require('./scripts/modal');

require('../robust-admin/js/core/app-menu');
require('../robust-admin/js/core/app');

require('../js/pages');


