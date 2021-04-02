Upgrading
============

To upgrade from mPDF 5.5 to 5.6, simply upload all the files to their corresponding folders, overwriting files as required.

You will need to make the following edits (additions) to your config.php file:

$this->defaultCSS = array(
	...
	'MARK' => array(
		'BACKGROUND-COLOR' => 'yellow',
	),
);

$this->outerblocktags = array('DIV','FORM','CENTER','DL','FIELDSET','ARTICLE','ASIDE','FIGURE','FIGCAPTION', 'FOOTER','HEADER','HGROUP','NAV','SECTION','DETAILS','SUMMARY');	// mPDF 5.5.09 // mPDF 5.5.22

$this->allowedCSStags = 'DIV|P|H1|H2|H3|H4|H5|H6|FORM|IMG|A|BODY|TABLE|HR|THEAD|TFOOT|TBODY|TH|TR|TD|UL|OL|LI|PRE|BLOCKQUOTE|ADDRESS|DL|DT|DD';
$this->allowedCSStags .= '|ARTICLE|ASIDE|FIGURE|FIGCAPTION|FOOTER|HEADER|HGROUP|NAV|SECTION|MARK|DETAILS|SUMMARY|METER|PROGRESS|TIME';
$this->allowedCSStags .= '|SPAN|TT|I|B|BIG|SMALL|EM|STRONG|DFN|CODE|SAMP|KBD|VAR|CITE|ABBR|ACRONYM|STRIKE|S|U|DEL|INS|Q|FONT';
$this->allowedCSStags .= '|SELECT|INPUT|TEXTAREA|CAPTION|FIELDSET|LEGEND';
$this->allowedCSStags .= '|TEXTCIRCLE';



