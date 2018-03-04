(function () {
    'use strict';

    angular.module('rurka').controller('enterController', ['$scope', function ($scope) {
        $scope.enterClick = function() {
            // window.location.href = vk;
            $.ajax({
                type: "POST",
                url: "vk/getCodeUrl",
                success: function(response) {
                    window.location.href = response.url;
                }
            });
        };

        $scope.exitClick = function() {
            window.location.href = "http://rurka.loc/index/logOut";
        };
    }]);
})();