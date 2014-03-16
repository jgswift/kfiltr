<?php
namespace kfiltr\Tests {
    use kfiltr;
    
    class FilterTest extends KfiltrTestCase {
        function testFilterInvoke() {
            $fooFilter = new Mock\FooFilter();
            
            $value = $fooFilter('foo');
            
            $this->assertEquals('foo',$value);
        }
        
        function testFilterDelegateInvoke() {
            $fooFilter = new Mock\FooFilter();
            
            $count = 0;
            $fooFilter->setDelegate(function()use(&$count) {
                $count++;
            });
            
            $fooFilter();
            
            $this->assertEquals(1,$count);
        }
        
        function testHookFilterInvoke() {
            $fooHook = new Mock\FooHook();
            
            $fooHook->addFilter(new Mock\FooFilter());
            
            $result = $fooHook('foo');
            
            $count = count($result);
            
            $this->assertEquals(1,$count);
        }
        
        function testMapperFilterInvoke() {
            $fooFactory = new Mock\FooFactory();
            $fooMapper = new Mock\FooMapper($fooFactory);
            
            $value = $fooMapper([],'kfiltr\Tests\Mock\FooFilter');
                        
            $isFilter = $value instanceof Mock\FooFilter;
            
            $this->assertEquals(true,$isFilter);
        }
        
        function testFactoryFilterInvoke() {
            $fooMapper = new Mock\FooFactoryFilter();
            
            $mapping = new kfiltr\Filter\Mapping([
                'filter' => 'kfiltr\Tests\Mock\FooFilter'
            ]);
            
            $fooMapper->setMapping($mapping);
            
            $value = $fooMapper([],'filter');
            
            $isFilter = $value instanceof Mock\FooFilter;
            
            $this->assertEquals(true,$isFilter);
        }
    }
}