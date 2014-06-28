<?php
/**
 * listDocumentType
 *
 * @author mellowonpsx
 * @author aci
 */
require_once 'utils.php';

echo json_ok(Document::listDocumentType());
exit();