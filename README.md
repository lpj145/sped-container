Essa lib é um container para dados aleatórios, onde imposto regras de um lado e dados do outro ele tentará executar as regras nos respectivos dados.

##### Instalação
````php
composer require marcosadantas/sped-container
````

##### Classes de interesse
````php
AbstractAttribute
SpedContainer
````

##### Interface para atributos
````php
SpedAttribute
````

##### Como usar
````php

$data = [
  'produto' => [
    'nome' => 'Produto PHP'
  ]
];

class ProdutoAttribute extends AbstractAttribute implements SpedAttribute 
{
   // O container fará a injeção automaticamente
    __invoke(array $items, OutroAttributo $instancia);
}

$regras = [
  ProdutosAttribute::class
]

$spedContainer = new SpedContainer($regras, $data);
$spedContainer->execute();

````


##### Desempenho
O container usa solução baseada em reflection e pra isso foi implementado o metódo ``getConfigCache`` que retornará o array da configuração atual, caso o guarde num arquivo, o desempenho melhorará significativamente.

##### Injeção
Basicamente todo atributo só estará disponivel para uso depois de executado suas "regras" no entando, antes de injetar o container se certifica e executa isto.

##### AbstractAttribute
Há algumas necessidades conforme implementar a interface, essa classe abstrata acabará com boa parte delas, também ela tem implementada por padrão o uso de traits para:
````php
Precision - numeros precisos
SanitizeString - limpeza de string
DateFormat - Formatação para UTC 
````