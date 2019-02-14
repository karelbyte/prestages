<ul ng-if="totalpage > 1" class="pagination">
    <li ng-class="{disabled: currentpage ==1}"><a href="" ng-click = "setpage(1)"> <span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span></a></li>
    <li ng-class="{disabled: currentpage ==1}"><a href="" ng-click = "setpage(currentpage - 1)"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span></a></li>
    <li ng-repeat="page in rango(totalpage,currentpage)" ng-class="{active:currentpage == page}"><a href="" ng-click = "setpage(page)" > <%page%></a></li>
    <li ng-class="{disabled: currentpage == totalpage}" ><a href=""  ng-click = "setpage(currentpage + 1)"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a></li>
    <li ng-class="{disabled: currentpage == totalpage}"><a href="" ng-click = "setpage(totalpage)"><span class="glyphicon glyphicon-step-forward" aria-hidden="true"></span></a></li>
</ul>