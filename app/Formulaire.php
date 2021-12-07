                               
<?php
/*---------------------------------------------------------------*/
/*
    Titre             : Formulaire.php
    Description       : Classe permettant de gérer des formulaires

    URL               : https://phpsources.net/code_s.php?id=160
    Date mise à jour  : 18 Aout 2019
    Rapport de la maj :
      - fonctionnement du code vérifié

    Documentation     : @Mar-Nb (2021)
*/
/*---------------------------------------------------------------*/
class Formulaire {

  // propriétés privées : tous les éléments et attributs utilisables
  // (certaines valeurs sont entrées par défaut)
  private $eventArr = array(
    'onfocus' => '',
    'onblur' => '',
    'onselect' => '',
    'onchange' => '',
    'onclick' => '',
    'ondblclick' => '',
    'onmousedown' => '',
    'onmouseup' => '',
    'onmouseover' => '',
    'onmousemove' => '',
    'onmouseout' => '',
    'onkeypress' => '',
    'onkeydown' => '',
    'onkeyup' => ''
  );
  private $commonArr = array(
    'id' => '',
    'class' => '',
    'title' => '',
    'style' => '',
    'dir' => '',
    'lang' => '',
    'xml:lang' => ''
  );
  private $formArr = array(
    'method' => 'post',
    'action' => '',
    'id' => 'mainForm',
    'enctype' => 'application/x-www-form-urlencoded',
    'accept' => '',
    'onsubmit' => '',
    'onreset' => '',
    'accept-charset' => 'unknown',
    'style' => ''
  );
  private $inputArr = array(
    'text' => array(
      'value' => '',
      'name' => '',
      'alt' => '',
      'tabindex' => '',
      'accesskey' => '',
      'readonly' => '',
      'disabled' => '',
      'width' => '',
      'maxlength' => ''
    ),
    'button' => array(
      'name' => '',
      'value' => '',
      'alt' => '',
      'tabindex' => '',
      'accesskey' => '',
      'disabled' => ''
    ),
    'hidden' => array(
      'name' => '',
      'value' => '',
      'alt' => '',
      'disabled' => ''
    ),
    'password' => array(
      'name' => '',
      'value' => '',
      'alt' => '',
      'tabindex' => '',
      'accesskey' => '',
      'readonly' => '',
      'disabled' => '',
      'width' => '',
      'maxlength' => ''
    ),
    'submit' => array(
      'name' => '',
      'value' => '',
      'alt' => '',
      'tabindex' => '',
      'accesskey' => '',
      'disabled' => ''
    ),
    'checkbox' => array(
      'name' => '',
      'value' => '',
      'alt' => '',
      'tabindex' => '',
      'accesskey' => '',
      'disabled' => '',
      'checked' => ''
    ),
    'radio' => array(
      'name' => '',
      'value' => '',
      'alt' => '',
      'tabindex' => '',
      'accesskey' => '',
      'disabled' => '',
      'checked' => '',
      'title' => ''
    ),
    'reset' => array(
      'name' => '',
      'class' => '',
      'value' => '',
      'alt' => '',
      'tabindex' => '',
      'accesskey' => '',
      'disabled' => '',
      'title' => ''
    ),
    'file' => array(
      'name' => '',
      'value' => '',
      'alt' => '',
      'tabindex' => '',
      'accesskey' => '',
      'disabled' => '',
      'accept' => ''
    ),
    'image' => array(
      'name' => '',
      'value' => '',
      'alt' => '',
      'tabindex' => '',
      'accesskey' => '',
      'disabled' => '',
      'src' => '',
      'usemap' => '',
      'ismap' => ''
    )
  );
  private $fieldsetArr = array();
  private $legendArr = array();
  private $labelArr = array('for' => '');
  private $textareaArr = array(
    'rows' => '',
    'cols' => '',
    'disabled' => '',
    'readonly' => '',
    'accesskey' => '',
    'tabindex' => '',
    'name' => ''
  );
  private $selectArr = array(
    'disabled' => '',
    'multiple' => '',
    'size' => '',
    'name' => ''
  );
  private $optionArr = array(
    'disabled' => '',
    'label' => '',
    'selected' => '',
    'value' => ''
  );
  private $optgroupArr = array('disabled' => '');
  private $formBuffer = array();
  private $formElementArr = array();
  private $formAttributeArr = array();

  /**
   * Constructeur
   *
   * Constructeur vide de la classe Formulaire
   **/
  public function __construct() { }

  /**
   * Ouverture du HTML d'un `form`
   *
   * Ouvre la balise `form` avec les attributs donnés en paramètre.
   *
   * @param Array $arrArgs Les attributs à donner au formulaire
   **/
  public function openForm($arrArgs = array()) {
    foreach ($this->formArr as $clef => $val) {
      if (array_key_exists($clef, $arrArgs)) {
        $this->formAttributeArr[$clef] = $arrArgs[$clef];
      } else if (!empty($val)) {
        $this->formAttributeArr[$clef] = $val;
      }
    }

    $this->formBuffer['open'] = '<form ';
    foreach ($this->formAttributeArr as $clef => $val) {
      $this->formBuffer['open'] .= $clef . '="' . $val . '" ';
    }

    $this->formBuffer['open'] .= '>';
  }

  /**
   * Fermeture du HTML d'un `form`
   */
  public function closeForm() { $this->formBuffer['close'] = '</form>'; }

  /**
   * Ajout d'un `input`
   *
   * Ajoute un `input` dans le formulaire, avec les attributs passés en paramètre.
   *
   * @param Mixed $elem Le type d'input (text, search, ...)
   * @param Array $arrArgs Les attributs de l'input
   * @throws Exception Exception pour un type d'input invalide (autre que text, search, ...)
   **/
  public function addInput($elem, $arrArgs = array()) {
    if (!array_key_exists($elem, $this->inputArr)) {
      throw new Exception($elem . ' n\'est pas un élément valide');
    }

    if (
      !array_key_exists('name', $arrArgs)
      && $elem !== 'submit'
      && $elem !== 'reset'
    ) {
      $arrArgs['name'] = 'default';
    }

    $cpt = count($this->formElementArr);
    $this->formElementArr[$cpt][$elem] = array();
    $arrTemp = array_merge(
      $this->eventArr,
      $this->commonArr,
      $this->inputArr[$elem]
    );

    foreach ($arrTemp as $clef => $val) {
      if (array_key_exists($clef, $arrArgs)) {
        $this->formElementArr[$cpt][$elem][$clef] = $arrArgs[$clef];
      }
    }

    $chaineTemp = '<input type="' . $elem . '" ';
    foreach ($this->formElementArr[$cpt][$elem] as $clef => $val) {
      $chaineTemp .= $clef . '="' . $val . '" ';
    }

    $chaineTemp .= '/>';
    $this->formBuffer['elements'][$cpt] = $chaineTemp;
  }

  /**
   * Création d'un fieldset
   *
   * Crée un élément `fieldset` dans le formulaire, avec les attributs passés en paramètre.
   *
   * @param Array $arrArgs Les attributs du fieldset
   **/
  public function openFieldset($arrArgs = array()) {
    $cpt = count($this->formElementArr);
    $this->formElementArr[$cpt]['fieldset'] = array();
    $arrTemp = array_merge(
      $this->eventArr,
      $this->commonArr,
      $this->fieldsetArr
    );

    foreach ($arrTemp as $clef => $val) {
      if (array_key_exists($clef, $arrArgs)) {
        $this->formElementArr[$cpt]['fieldset'][$clef] = $arrArgs[$clef];
      }
    }

    $chaineTemp = '<fieldset ';
    foreach ($this->formElementArr[$cpt]['fieldset'] as $clef => $val) {
      $chaineTemp .= $clef . '="' . $val . '" ';
    }

    $chaineTemp .= '>';
    $this->formBuffer['elements'][$cpt] = $chaineTemp;
  }

  /**
   * Fermeture du HTML d'un `fieldset`
   */
  public function closeFieldset() {
    $cpt = count($this->formElementArr);
    $this->formElementArr[$cpt]['/fieldset'] = array();
    $chaineTemp = '</fieldset>';
    $this->formBuffer['elements'][$cpt] = $chaineTemp;
  }

  /**
   * Ajout d'un élément `legend`
   * 
   * Ajoute un élément `legend` associé au fieldset, avec les attributs passés en paramètre
   * 
   * @param Mixed $legend Le texte de la légende du fieldset
   * @param Array $arrArgs Les attributs de la légende
   */
  public function addLegend($legend, $arrArgs = array()) {
    $cpt = count($this->formElementArr);
    $this->formElementArr[$cpt]['legend']['innerHTML'] = $legend;
    $arrTemp = array_merge(
      $this->eventArr,
      $this->commonArr,
      $this->legendArr
    );

    foreach ($arrTemp as $clef => $val) {
      if (array_key_exists($clef, $arrArgs)) {
        $this->formElementArr[$cpt]['legend'][$clef] = $arrArgs[$clef];
      }
    }

    $chaineTemp = '<legend ';
    foreach ($this->formElementArr[$cpt]['legend'] as $clef => $val) {
      if ($clef !== 'innerHTML') {
        $chaineTemp .= $clef . '="' . $val . '" ';
      }
    }

    $chaineTemp .= '>' . $legend . '</legend>';
    $this->formBuffer['elements'][$cpt] = $chaineTemp;
  }

  /**
   * Ouverture d'une balise `p`
   * 
   * Ouvre une nouvelle balise `p` dans le formulaire, avec les attributs passés en paramètre
   * 
   * @param Array $arrArgs Les attributs du paragraphe
   */
  public function openP($arrArgs = array()) {
    $cpt = count($this->formElementArr);
    $this->formElementArr[$cpt]['p'] = array();
    $arrTemp = array_merge(
      $this->eventArr,
      $this->commonArr,
      $this->pArr
    );

    foreach ($arrTemp as $clef => $val) {
      if (array_key_exists($clef, $arrArgs)) {
        $this->formElementArr[$cpt]['p'][$clef] = $arrArgs[$clef];
      }
    }

    $chaineTemp = '<p ';
    foreach ($this->formElementArr[$cpt]['p'] as $clef => $val) {
      $chaineTemp .= $clef . '="' . $val . '" ';
    }

    $chaineTemp .= '>';
    $this->formBuffer['elements'][$cpt] = $chaineTemp;
  }

  /**
   * Fermeture de la balise `p`
   */
  public function closeP() {
    $cpt = count($this->formElementArr);
    $this->formElementArr[$cpt]['/p'] = array();
    $chaineTemp = '</p>';
    $this->formBuffer['elements'][$cpt] = $chaineTemp;
  }

  /**
   * Ajout d'un élément `label`
   * 
   * Ajoute un élément `label` associé à un input, avec les attributs passés en paramètre
   * 
   * @param Mixed $label Le texte du label de l'input
   * @param Array $arrArgs Les attributs du label
   */
  public function addLabel($label, $arrArgs = array()) {
    $cpt = count($this->formElementArr);
    $this->formElementArr[$cpt]['label']['innerHTML'] = $label;
    $arrTemp = array_merge(
      $this->eventArr,
      $this->commonArr,
      $this->labelArr
    );

    foreach ($arrTemp as $clef => $val) {
      if (array_key_exists($clef, $arrArgs)) {
        $this->formElementArr[$cpt]['label'][$clef] = $arrArgs[$clef];
      }
    }

    $chaineTemp = '<label ';
    foreach ($this->formElementArr[$cpt]['label'] as $clef => $val) {
      if ($clef !== 'innerHTML') {
        $chaineTemp .= $clef . '="' . $val . '" ';
      }
    }

    $chaineTemp .= '>' . $label . '</label>';
    $this->formBuffer['elements'][$cpt] = $chaineTemp;
  }

  /**
   * Ajout d'un élément `textarea`
   * 
   * Ajoute un élément `textarea`, avec les attributs passés en paramètre
   * 
   * @param Mixed $txt Le texte à l'intérieur du textarea
   * @param Array $arrArgs Les attributs de la zone de texte
   */
  public function addTextarea($txt, $arrArgs = array()) {
    $cpt = count($this->formElementArr);
    $this->formElementArr[$cpt]['textarea']['innerHTML'] = $txt;
    $arrTemp = array_merge(
      $this->eventArr,
      $this->commonArr,
      $this->textareaArr
    );

    foreach ($arrTemp as $clef => $val) {
      if (array_key_exists($clef, $arrArgs)) {
        $this->formElementArr[$cpt]['textarea'][$clef] = $arrArgs[$clef];
      }
    }

    $chaineTemp = '<textarea ';
    foreach ($this->formElementArr[$cpt]['textarea'] as $clef => $val) {
      if ($clef !== 'innerHTML') {
        $chaineTemp .= $clef . '="' . $val . '" ';
      }
    }

    $chaineTemp .= '>' . $txt . '</textarea>';
    $this->formBuffer['elements'][$cpt] = $chaineTemp;
  }

    /**
   * Ouverture d'une balise `select`
   * 
   * Ouvre une nouvelle balise `select` dans le formulaire, avec les attributs passés en paramètre
   * 
   * @param Array $arrArgs Les attributs du select
   */
  public function openSelect($arrArgs = array()) {
    $cpt = count($this->formElementArr);
    $this->formElementArr[$cpt]['select'] = array();
    $arrTemp = array_merge(
      $this->eventArr,
      $this->commonArr,
      $this->selectArr
    );

    foreach ($arrTemp as $clef => $val) {
      if (array_key_exists($clef, $arrArgs)) {
        $this->formElementArr[$cpt]['select'][$clef] = $arrArgs[$clef];
      }
    }

    $chaineTemp = '<select ';
    foreach ($this->formElementArr[$cpt]['select'] as $clef => $val) {
      $chaineTemp .= $clef . '="' . $val . '" ';
    }

    $chaineTemp .= '>';
    $this->formBuffer['elements'][$cpt] = $chaineTemp;
  }

  /**
   * Fermeture d'une balise `select`
   */
  public function closeSelect() {
    $cpt = count($this->formElementArr);
    $this->formElementArr[$cpt]['/select'] = array();
    $chaineTemp = '</select>';
    $this->formBuffer['elements'][$cpt] = $chaineTemp;
  }

  /**
   * Ajout d'un élément `option`
   * 
   * Ajoute un élément `option`, avec les attributs passés en paramètre
   * 
   * @param Mixed $txt Le texte de l'option
   * @param Array $arrArgs Les attributs de l'option
   */
  public function addOption($txt, $arrArgs = array()) {
    $cpt = count($this->formElementArr);
    $this->formElementArr[$cpt]['option']['innerHTML'] = $txt;
    $arrTemp = array_merge(
      $this->eventArr,
      $this->commonArr,
      $this->optionArr
    );

    foreach ($arrTemp as $clef => $val) {
      if (array_key_exists($clef, $arrArgs)) {
        $this->formElementArr[$cpt]['option'][$clef] = $arrArgs[$clef];
      }
    }

    $chaineTemp = '<option ';
    foreach ($this->formElementArr[$cpt]['option'] as $clef => $val) {
      if ($clef !== 'innerHTML') {
        $chaineTemp .= $clef . '="' . $val . '" ';
      }
    }

    $chaineTemp .= '>' . $txt . '</option>';
    $this->formBuffer['elements'][$cpt] = $chaineTemp;
  }

  /**
   * Ouverture d'une balise `optgroup`
   * 
   * Ouvre une nouvelle balise `optgroup` dans un `select`, avec les attributs passés en paramètre
   * 
   * @param Mixed $label Le label de la balise `optgroup`
   * @param Array $arrArgs Les attributs de la balise `optgroup`
   */
  public function openOptgroup($label, $arrArgs = array()) {
    $cpt = count($this->formElementArr);
    $this->formElementArr[$cpt]['optgroup']['label'] = $label;
    $arrTemp = array_merge(
      $this->eventArr,
      $this->commonArr,
      $this->optgroupArr
    );

    foreach ($arrTemp as $clef => $val) {
      if (array_key_exists($clef, $arrArgs)) {
        $this->formElementArr[$cpt]['select'][$clef] = $arrArgs[$clef];
      }
    }

    $chaineTemp = '<optgroup ';
    foreach ($this->formElementArr[$cpt]['optgroup'] as $clef => $val) {
      $chaineTemp .= $clef . '="' . $val . '" ';
    }
    
    $chaineTemp .= '>';
    $this->formBuffer['elements'][$cpt] = $chaineTemp;
  }

  /**
   * Fermeture d'une balise `optgroup`
   */
  public function closeOptgroup() {
    $cpt = count($this->formElementArr);
    $this->formElementArr[$cpt]['/optgroup'] = array();
    $chaineTemp = '</optgroup>';
    $this->formBuffer['elements'][$cpt] = $chaineTemp;
  }

  /**
   * Ajout d'un élément quelconque dans le formulaire
   * 
   * @param Mixed $any L'élément à ajouter
   */
  public function addAnything($any) {
    $cpt = count($this->formElementArr);
    $this->formBuffer['anything'][$cpt] = $any;
  }

  /**
   * Méthode magique utilisée pour afficher effectivement le formulaire défini
   */
  public function __toString() {
    $chaineTemp = $this->formBuffer['open'];
    foreach ($this->formBuffer['elements'] as $clef => $val) {
      if (isset($this->formBuffer['anything'][$clef])) {
        $chaineTemp .= $this->formBuffer['anything'][$clef];
      }

      $chaineTemp .= $val;
    }

    $chaineTemp .= $this->formBuffer['close'];
    return $chaineTemp;
  }

  /**
   * Méthode pour libérer les ressources et créer un nouveau formulaire
   * (tout formulaire créé aurapavant et non affiché sera perdu)
   */
  public function freeForm() {
    $this->formBuffer = array();
    $this->formElementArr = array();
    $this->formAttributeArr = array();
  }

  /***************************
   ***METHODS FOR DEBUGGING***
   ***************************/

  /**
   * Méthode affichant tous les éléments que contient le formulaire
   */
  public function showElems() {
    $chaineTemp = '';
    foreach ($this->formElementArr as $clef => $val) {

      foreach ($val as $elem => $attrArr) {
        if (strpos($elem, '/') !== false) {
          $chaineTemp .= '<ul><li style="color: blue;">
                           end ' . substr($elem, 1, strlen($elem)) . '
                           </li></ul>';
        } else {
          $chaineTemp .= '<ul><li style="color: blue;">' . $elem . '</li><ul>';

          foreach ($attrArr as $attr => $value) {
            $chaineTemp .= '<li style="color: red;">
                           ' . $attr . ' =
                           <span style="color: green; font-style: italic;">
                           ' . $value . '</span></li>';
          }

          $chaineTemp .= '</ul></ul>';
        }
      }
    }

    return $chaineTemp;
  }

  /**
   * Méthode comptant les éléments que contient le formulaire : total global, et total par élément
   */
  public function countElems() {
    foreach ($this->formElementArr as $clef => $val) {
      foreach ($val as $elem => $attrArr) {
        if (strpos($elem, '/') === false) {
          $arrTemp[] = $elem;
        }
      }
    }

    $cptElem = count($arrTemp);
    $arrEachElem = array_count_values($arrTemp);
    $chaineTemp = '<span style="color: black; font-weight: bold;">
                    Total éléments : <span style="color: red;">
                    ' . $cptElem . '</span><br />dont : </span><br />';
    ksort($arrEachElem, SORT_STRING);

    foreach ($arrEachElem as $elem => $nbr) {
      $chaineTemp .= '<span style="color: blue; margin-left: 20px;">
                     ' . $elem . ' : </span><span style="color: red;">
                     ' . $nbr . '</span><br />';
    }
    
    return $chaineTemp;
  }
}

// on instancie notre objet
$form = new Formulaire();
// on crée un 1er formulaire
$form->openForm(array('action' => '?', 'id' => 'MyForm'));
$form->openFieldset(array('style' => 'border:1px dotted red; width: 300px;'));
$form->addLegend('test');
$form->addInput('text', array('id' => 'MyText', 'value' => 'ok', 'test' => 'test'));
$form->addLabel('label', array('for' => 'MyText', 'style' => 'margin: 5px;'));
$form->addAnything('<br /><br />');
$form->addInput('button', array('id' => 'MyButton', 'value' => 'click!', 'test' => 'test'));
$form->closeFieldset();
$form->closeForm();

echo '<div style="border:1px solid darkgrey;text-align:center;width:310px;">';
// on l'affiche
echo $form;
echo '</div>';

// on compte et affiche ses éléments (debugging only)
echo $form->showElems();
echo $form->countElems();

// on libère les ressources pour pouvoir créer un 2d formulaire
$form->freeForm();

// on réinitialise un nouveau formulaire
// on ouvre effectivement le nouveau formulaire
$form->openForm(array('action' => '?', 'id' => 'MyForm2'));
$form->openFieldset(array('style' => 'border:1px dotted blue; width: 300px;'));
$form->addLegend('test 2');
$form->addInput('text', array('id' => 'MyText2', 'value' => 'yep', 'test' => 'test'));
$form->addInput('checkbox', array('id' => 'MyCheck', 'value' => '1', 'test' => 'test'));
$form->addLabel('Checkbox', array('for' => 'MyCheck'));
$form->addTextarea('mon texte', array('cols' => 20, 'rows' => 10));
$form->openSelect();
$form->openOptgroup('label options 1');
$form->addOption('1');
$form->closeOptgroup();
$form->openOptgroup('label options 2');
$form->addOption('2');
$form->addOption('2_2');
$form->closeOptgroup();
$form->closeSelect();
$form->closeFieldset();
$form->closeForm();

echo '<div style="border:1px solid orange;text-align:center; width:310px;">';
echo $form;
echo '</div>';

echo $form->showElems();
echo $form->countElems();

$form->freeForm();

?>
