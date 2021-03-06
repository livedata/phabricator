<?php

/*
 * Copyright 2012 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

final class PhabricatorMainMenuSearchView extends AphrontView {

  private $user;
  private $scope;
  private $id;

  public function setUser(PhabricatorUser $user) {
    $this->user = $user;
    return $this;
  }

  public function setScope($scope) {
    $this->scope = $scope;
    return $this;
  }

  public function getID() {
    if (!$this->id) {
      $this->id = celerity_generate_unique_node_id();
    }
    return $this->id;
  }

  public function render() {
    $user = $this->user;

    $search_id = $this->getID();

    $input = phutil_render_tag(
      'input',
      array(
        'type' => 'text',
        'name' => 'query',
        'id'   => $search_id,
      ));

    $scope = $this->scope;

    Javelin::initBehavior(
      'placeholder',
      array(
        'id' => $search_id,
        'text' => PhabricatorSearchScope::getScopePlaceholder($scope),
      ));

    $scope_input = phutil_render_tag(
      'input',
      array(
        'type' => 'hidden',
        'name' => 'scope',
        'value' => $scope,
      ));

    $form = phabricator_render_form(
      $user,
      array(
        'action' => '/search/',
        'method' => 'POST',
      ),
      '<div class="phabricator-main-menu-search-container">'.
        $input.
        '<button>Search</button>'.
        $scope_input.
      '</div>');

    $group = new PhabricatorMainMenuGroupView();
    $group->addClass('phabricator-main-menu-search');
    $group->appendChild($form);
    return $group->render();
  }

}
