define(['bootstrap3'], function(bootstrap3) {
	return {
		build : function(options){
			var title = options.title ? options.title : '提示';
			var content = '';
			if(typeof(options.content) == 'string'){
				content = options.content;
			}else if(typeof(options.content) == 'function'){
				content = options.content();
			}

			var footer = '';
			if(typeof(options.footer) == 'string'){
				footer = options.footer;
			}else if(typeof(options.footer) == 'function'){
				footer = options.footer();
			}

			var width = options.width ? options.width : 800,
				height = options.height ? options.height : 600;

			var dialogClass = options.dialogClass ? ' ' + options.dialogClass : '';

			var $dialog = $('<div class="modal fade">\
				<div class="modal-dialog' + dialogClass + '" role="document">\
					<div class="modal-content" style="width:' + width + 'px;height:' + height + 'px">\
						<div class="modal-header">\
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>\
							<h4 class="modal-title J-title">' + title + '</h4>\
						</div>\
						<div class="modal-body J-content">' + content + '</div>\
						<div class="modal-footer J-footer">' + footer + '</div>\
					</div>\
				</div>\
			</div>');

			$dialog.show = function(){
				this.modal('show');
			};

			$dialog.on('hidden.bs.modal', function(){
				$(this).remove();
			});

			return $dialog;
		}
	};
});