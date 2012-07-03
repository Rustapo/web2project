<?php /* $Id$ $URL$ */
if (!defined('W2P_BASE_DIR')) {
	die('You should not access this file directly.');
}

global $search_string, $owner_filter_id, $currentTabId, $orderby, $orderdir;

$types = w2PgetSysVal('DepartmentType');
$dept_type_filter = $currentTabId-1;

// get any records denied from viewing

$dept = new CDepartment();
$depts = $dept->getFilteredDepartmentList(null, $dept_type_filter, $search_string, $owner_filter_id, $orderby, $orderdir);
?>
<table class="tbl list">
    <tr>
        <?php
        $fieldList = array();
        $fieldNames = array();

        $module = new w2p_Core_Module();
        $fields = $module->loadSettings('departments', 'index_list');

        if (count($fields) > 0) {
            $fieldList = array_keys($fields);
            $fieldNames = array_values($fields);
        } else {
            // TODO: This is only in place to provide an pre-upgrade-safe 
            //   state for versions earlier than v3.0
            //   At some point at/after v4.0, this should be deprecated
            $fieldList = array('dept_name', 'countp', 'inactive', 'dept_type');
            $fieldNames = array('Department Name', 'Active Projects', 'Archived Projects', 'Type');
        }
//TODO: The link below is commented out because this module doesn't support sorting... yet.
        foreach ($fieldNames as $index => $name) {
            ?><th nowrap="nowrap">
<!--                <a href="?m=departments&orderby=<?php echo $fieldList[$index]; ?>" class="hdr">-->
                    <?php echo $AppUI->_($fieldNames[$index]); ?>
<!--                </a>-->
            </th><?php
        }
        ?>
    </tr>
<?php
if (count($depts)) {
	$htmlHelper = new w2p_Output_HTMLHelper($AppUI);

    foreach ($depts as $row) {
        echo '<tr>';
        $htmlHelper->stageRowData($row);
//TODO: how do we tweak this to get the parent/child relationship to display?
        foreach ($fieldList as $index => $column) {
            echo $htmlHelper->createCell($fieldList[$index], $row[$fieldList[$index]], $customLookups);
        }
        echo '</tr>';
	}
} else {
    echo '<tr><td colspan="'.count($fieldNames).'">' . $AppUI->_('No data available') . '</td></tr>';
}
?>
</table>