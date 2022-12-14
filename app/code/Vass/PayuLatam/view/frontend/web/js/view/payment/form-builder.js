define(
	[
		'jquery',
		'underscore',
		'mage/template'
	],
	function ($, _, mageTemplate) {
		'use strict';

		/* This is the form template used to generate the form, from your Controller JSON data */
		var formTemplate =
			`<form action="<%= data.action %>" method="POST" hidden enctype="application/x-www-form-urlencoded">
				<% _.each(data.fields, function (val, key) { %>
					<input value="<%= val %>" name="<%= key %>" type="hidden">
				<% }); %>
			</form>`;
		return function (response) {
			var form = mageTemplate(formTemplate, {
				data: {
					action: response.action, //notice the "action" key is the form action you return from controller
					fields: response.fields //fields are inputs of the form returned from controller
				}
			});

			return $(form).appendTo($('[data-container="body"]')); //Finally, append the form to the <body> element (or more accurately to the [data-container="body"] element)
		};
	}
);