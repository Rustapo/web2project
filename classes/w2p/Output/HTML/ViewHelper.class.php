<?php
/**
 * Class w2p_Output_HTML_FormHelper
 *
 * @package     web2project\output\html
 * @author      D. Keith Casey, Jr. <contrib@caseysoftware.com>
 */
class w2p_Output_HTML_ViewHelper extends w2p_Output_HTML_Base
{
    public function addField($fieldName, $fieldValue)
    {
        if ('' == $fieldValue) {
            return '-';
        }

        $pieces = explode('_', $fieldName);
        $suffix = end($pieces);

        switch($suffix) {
            case 'datetime':
                $myDate = intval($fieldValue) ? new w2p_Utilities_Date($this->AppUI->formatTZAwareTime($fieldValue, '%Y-%m-%d %T')) : null;
                $output = $myDate ? $myDate->format($this->dtf) : '-';
                break;
            case 'email':
                $field = new Web2project\Fields\Email();
                $output = $field->view($fieldValue);
                break;
            case 'url':
                $field = new Web2project\Fields\Url();
                $output = $field->view($fieldValue);
                break;
            case 'owner':
                $obj = new CContact();
                $obj->findContactByUserid($fieldValue);

                $field = new Web2project\Fields\Module();
                $field->setObject($obj, 'user');

                $output = $field->view($fieldValue);
                break;
            case 'percent':
                $field = new Web2project\Fields\Percent();
                $output = $field->view($fieldValue);
                break;
            case 'description':
                $field = new Web2project\Fields\Text();
                $output = $field->view($fieldValue);
                break;
            case 'company':
            case 'department':
            case 'project':
                $class  = 'C'.ucfirst($suffix);
                $obj = new $class();
                $obj->load($fieldValue);

                $field = new Web2project\Fields\Module();
                $field->setObject($obj, $suffix);

                $output = $field->view($fieldValue);
                break;
            default:
                $field = new Web2project\Fields\Text();
                $output = $field->view($fieldValue);
        }

        return $output;
    }

    public function showField($fieldName, $fieldValue)
    {
        echo $this->addField($fieldName, $fieldValue);
    }

    public function showAddress($name, $object)
    {
        $countries = w2PgetSysVal('GlobalCountries');

        $output  = '<div style="margin-left: 11em;">';
        $output .= '<a href="http://maps.google.com/maps?q=' . $object->{$name . '_address1'} . '+' . $object->{$name . '_address2'} . '+' . $object->{$name . '_city'} . '+' . $object->{$name . '_state'} . '+' . $object->{$name . '_zip'} . '+' . $object->{$name . '_country'} . '" target="_blank">';
        $output .= '<img src="' . w2PfindImage('googlemaps.gif') . '" class="right" alt="Find It on Google" />';
        $output .= '</a>';
        $output .=  $object->{$name . '_address1'} . (($object->{$name . '_address2'}) ? '<br />' . $object->{$name . '_address2'} : '') . (($object->{$name . '_city'}) ? '<br />' . $object->{$name . '_city'} : '') . (($object->{$name . '_state'}) ? ' ' . $object->{$name . '_state'} : '') . (($object->{$name . '_zip'}) ? ', ' . $object->{$name . '_zip'} : '') . (($object->{$name . '_country'}) ? '<br />' . $countries[$object->{$name . '_country'}] : '');
        $output .= '</div>';

        echo $output;
    }
}