<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Developer Developer
 * @ingroup     UnaModules
 *
 * @{
 */

require_once('BxDevFunctions.php');

interface iBxDevFormsField
{
    const mixedChangeFormField = ['BxDevFunctions', 'changeFormField'];
    const mixedChangeFormAdd = ['BxDevFunctions', 'changeForm'];
    const mixedChangeFormEdit = ['BxDevFunctions', 'changeForm'];
}

class BxDevFormsField extends BxTemplStudioFormsField
{
    public function __construct($aParams = [], $aField = [])
    {
        parent::__construct($aParams, $aField);

        $this->aTypes = array_merge($this->aTypes, [
            'captcha' => ['add' => 1],
            'location' => ['add' => 1], 
            'custom' => ['add' => 1]
        ]);
    }
}

class BxDevFormsFieldBlockHeader extends BxTemplStudioFormsFieldBlockHeader implements iBxDevFormsField
{
}

class BxDevFormsFieldBlockEnd extends BxTemplStudioFormsFieldBlockEnd implements iBxDevFormsField
{
}

class BxDevFormsFieldValue extends BxTemplStudioFormsFieldValue implements iBxDevFormsField
{
}

class BxDevFormsFieldText extends BxTemplStudioFormsFieldText implements iBxDevFormsField
{
}

class BxDevFormsFieldPassword extends BxTemplStudioFormsFieldPassword implements iBxDevFormsField
{
}

class BxDevFormsFieldTextarea extends BxTemplStudioFormsFieldTextarea implements iBxDevFormsField
{
    public function init()
    {
        parent::init();

        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'required', [
            'unique' => $this->aFieldUnique
        ]);
    }
}

class BxDevFormsFieldNumber extends BxTemplStudioFormsFieldNumber implements iBxDevFormsField
{
}

class BxDevFormsFieldPrice extends BxTemplStudioFormsFieldPrice implements iBxDevFormsField
{
}

class BxDevFormsFieldDatepicker extends BxTemplStudioFormsFieldDatepicker implements iBxDevFormsField
{
}

class BxDevFormsFieldDateselect extends BxTemplStudioFormsFieldDateselect implements iBxDevFormsField
{
}

class BxDevFormsFieldDatetime extends BxTemplStudioFormsFieldDatetime implements iBxDevFormsField
{
}

class BxDevFormsFieldCheckbox extends BxTemplStudioFormsFieldCheckbox implements iBxDevFormsField
{
    public function init()
    {
        parent::init();

        $this->aForm['inputs']['value']['type'] = 'text';
    }
}

class BxDevFormsFieldSwitcher extends BxTemplStudioFormsFieldSwitcher implements iBxDevFormsField
{
    public function init()
    {
        parent::init();

        $this->aForm['inputs']['value']['type'] = 'text';
    }
}

class BxDevFormsFieldFile extends BxTemplStudioFormsFieldFile implements iBxDevFormsField
{
}

class BxDevFormsFieldFiles extends BxTemplStudioFormsFieldFiles implements iBxDevFormsField
{
}

class BxDevFormsFieldSlider extends BxTemplStudioFormsFieldSlider implements iBxDevFormsField
{
    public function init()
    {
        parent::init();

        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'required', [
            'unique' => $this->aFieldUnique
        ]);
    }
}

class BxDevFormsFieldDoublerange extends BxTemplStudioFormsFieldDoublerange implements iBxDevFormsField
{
    public function init()
    {
        parent::init();

        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'required', [
            'unique' => $this->aFieldUnique
        ]);
    }
}

class BxDevFormsFieldHidden extends BxTemplStudioFormsFieldHidden implements iBxDevFormsField
{
}

class BxDevFormsFieldButton extends BxTemplStudioFormsFieldButton implements iBxDevFormsField
{
}

class BxDevFormsFieldReset extends BxTemplStudioFormsFieldReset implements iBxDevFormsField
{
}

class BxDevFormsFieldSubmit extends BxTemplStudioFormsFieldSubmit implements iBxDevFormsField
{
}

class BxDevFormsFieldImage extends BxTemplStudioFormsFieldImage implements iBxDevFormsField
{
}

class BxDevFormsFieldSelect extends BxTemplStudioFormsFieldSelect implements iBxDevFormsField
{
    public function init()
    {
        parent::init();

        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'required', [
            'unique' => $this->aFieldUnique
        ]);
    }
}

class BxDevFormsFieldSelectMultiple extends BxTemplStudioFormsFieldSelectMultiple implements iBxDevFormsField
{
    public function init()
    {
        parent::init();

        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'required', [
            'unique' => $this->aFieldUnique
        ]);
    }
}

class BxDevFormsFieldCheckboxSet extends BxTemplStudioFormsFieldCheckboxSet implements iBxDevFormsField
{
    public function init()
    {
        parent::init();

        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'required', [
            'unique' => $this->aFieldUnique
        ]);
    }
}

class BxDevFormsFieldRadioSet extends BxTemplStudioFormsFieldRadioSet implements iBxDevFormsField
{
    public function init()
    {
        parent::init();

        $this->aForm['inputs'] = $this->addInArray($this->aForm['inputs'], 'required', [
            'unique' => $this->aFieldUnique
        ]);
    }
}

class BxDevFormsFieldCustom extends BxTemplStudioFormsFieldCustom implements iBxDevFormsField
{
}

class BxDevFormsFieldInputSet extends BxTemplStudioFormsFieldInputSet implements iBxDevFormsField
{
}

class BxDevFormsFieldCaptcha extends BxTemplStudioFormsFieldCaptcha implements iBxDevFormsField
{
}

class BxDevFormsFieldLocation extends BxTemplStudioFormsFieldLocation implements iBxDevFormsField
{
}
/** @} */
