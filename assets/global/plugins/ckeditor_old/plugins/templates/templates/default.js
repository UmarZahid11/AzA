/*
 Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.md or http://ckeditor.com/license
*/
CKEDITOR.addTemplates("default",{imagesPath:CKEDITOR.getUrl(CKEDITOR.plugins.getPath("templates")+"templates/images/"),templates:[
	{title:"Size Chart",image:"template3.gif",description:"Describe size chart table.",
			html:'<table class="data-table"> <thead> <tr align="center"> <th>Size</th> <th>SM</th> <th>MD</th> <th>LG</th> <th>XL</th> <th>2XL</th> <th>3XL</th> </tr> </thead> <tbody> <tr> <td style="padding:10px 0;">Chest</td> <td>21.5\'</td> <td>22.5\'</td> <td>24\'</td> <td>26\'</td> <td>-</td> <td>-</td> </tr> <tr> <td style="padding:10px 0;">Height</td> <td>21.5\'</td> <td>22.5\'</td> <td>24\'</td> <td>26\'</td> <td>-</td> <td>-</td> </tr> <tr> <td style="padding:10px 0;">Sleeves</td> <td>21.5\'</td> <td>22.5\'</td> <td>24\'</td> <td>26\'</td> <td>-</td> <td>-</td> </tr> <tr> <td style="padding:10px 0;">Waist</td> <td>21.5\'</td> <td>22.5\'</td> <td>24\'</td> <td>26\'</td> <td>-</td> <td>-</td> </tr> </tbody> </table>'
	},
	{title:"Image and Title",image:"template1.gif",description:"One main image with a title and text that surround the image.",html:'<h3><img src=" " alt="" style="margin-right: 10px" height="100" width="100" align="left" />Type the title here</h3><p>Type the text here</p>'},
	{title:"Strange Template",image:"template2.gif",description:"A template that defines two colums, each one with a title, and some text.",
		html:'<table cellspacing="0" cellpadding="0" style="width:100%" border="0"><tr><td style="width:50%"><h3>Title 1</h3></td><td></td><td style="width:50%"><h3>Title 2</h3></td></tr><tr><td>Text 1</td><td></td><td>Text 2</td></tr></table><p>More text goes here.</p>'},
]});