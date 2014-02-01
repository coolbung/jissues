/**
 * @copyright  Copyright (C) 2012 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

var JTracker = {};

JTracker.preview = function(text, preview) {
	var out = $(preview);

	out.html(g11n3t('Loading preview...'));

	$.post(
		'/preview',
		{ text: $(text).val() },
		function (r) {
			out.empty();
			if (r.error) {
				out.html(r.error);
			}
			else if (!r.data.length) {
				out.html(g11n3t('Invalid response.'));
			}
			else {
				out.html(r.data);
			}
		}
	);
};

JTracker.submitComment = function (issue_number, debugContainer, outContainer, template) {
	var out = $(outContainer);
	var status = $(debugContainer);

	status.html(g11n3t('Submitting comment...'));

	$.post(
		'/submit/comment',
		{ text: $('#comment').val(), issue_number: issue_number },
		function (r) {
			if (!r.data) {
				// Misc failure
				status.html(g11n3t('Invalid response.'));
			}
			else if (r.error) {
				// Failure
				status.html(r.error);
			}
			else {
				// Success
				status.html(r.message);

				out.html(out.html() + tmpl(template, r.data));

				// Clear textarea
				$('#comment').val('');
			}
		}
	);
};

JTracker.submitVote = function (issueId, debugContainer) {
	var status = $(debugContainer);
	var importance = $('input[name=importanceRadios]').filter(':checked').val();
	var experienced = $('input[name=experiencedRadios]').filter(':checked').val();

	status.addClass('disabled').removeAttr('href').removeAttr('onclick').html(g11n3t('Adding vote...'));

	$.post(
		'/submit/vote',
		{ issueId: issueId, experienced: experienced, importance: importance },
		function (r) {
			if (r.error) {
				// Failure
				status.addClass('btn-danger').removeClass('btn-success').html(r.error);
			}
			else {
				// Success
				status.html(r.message);

				// Update votes display if this is not the first vote on an item
				if (r.data.votes > 1) {
					$('td[id=votes]').html(r.data.votes);
					$('td[id=experienced]').html(r.data.experienced);
					$('td[id=importance]').html((r.data.importanceScore).toFixed(2));
				}
			}
		}
	);
};
